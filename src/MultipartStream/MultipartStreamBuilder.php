<?php

namespace Akeneo\Pim\ApiClient\MultipartStream;

use Http\Message\MultipartStream\ApacheMimetypeHelper;
use Http\Message\MultipartStream\MimetypeHelper;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @see https://github.com/php-http/multipart-stream-builder/blob/1.0.0/src/MultipartStreamBuilder.php
 *
 * It adapts the original class to accept a Psr\Http\Message\StreamFactoryInterface instead of Http\Message\MultipartStream\MultipartStreamBuilder
 */
class MultipartStreamBuilder
{
    /** @var MimetypeHelper */
    private $mimetypeHelper;

    private ?string $boundary = null;

    /**
     * @var array Element where each Element is an array with keys ['contents', 'headers', 'filename']
     */
    private array $data = [];


    public function __construct(
        private StreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * Add a resource to the Multipart Stream.
     *
     * @param string                          $name     the formpost name
     * @param string|resource|StreamInterface $resource
     * @param array                           $options  {
     *
     *     @var array $headers additional headers ['header-name' => 'header-value']
     *     @var string $filename
     * }
     *
     * @return MultipartStreamBuilder
     */
    public function addResource(string $name, $resource, array $options = [])
    {
        if (is_string($resource)) {
            $stream = $this->streamFactory->createStream($resource);
        } elseif (is_resource($resource)) {
            $stream = $this->streamFactory->createStreamFromResource($resource);
        } elseif ($resource instanceof StreamInterface) {
            $stream = $resource;
        } else {
            throw new \InvalidArgumentException('Resource should be a string, a resource or a Psr\Http\Message\StreamInterface');
        }

        // validate options['headers'] exists
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        // Try to add filename if it is missing
        if (empty($options['filename'])) {
            $options['filename'] = null;
            $uri = $stream->getMetadata('uri');
            if (!str_starts_with($uri, 'php://')) {
                $options['filename'] = $uri;
            }
        }

        $this->prepareHeaders($name, $stream, $options['filename'], $options['headers']);
        $this->data[] = ['contents' => $stream, 'headers' => $options['headers'], 'filename' => $options['filename']];

        return $this;
    }

    /**
     * Build the stream.
     *
     * @return StreamInterface
     */
    public function build()
    {
        $streams = '';
        foreach ($this->data as $data) {
            // Add start and headers
            $streams .= "--{$this->getBoundary()}\r\n" .
                $this->getHeaders($data['headers']) . "\r\n";

            // Convert the stream to string
            /* @var $contentStream StreamInterface */
            $contentStream = $data['contents'];
            if ($contentStream->isSeekable()) {
                $streams .= $contentStream->__toString();
            } else {
                $streams .= $contentStream->getContents();
            }

            $streams .= "\r\n";
        }

        // Append end
        $streams .= "--{$this->getBoundary()}--\r\n";

        return $this->streamFactory->createStream($streams);
    }

    /**
     * Add extra headers if they are missing.
     *
     * @param string          $name
     * @param StreamInterface $stream
     * @param string          $filename
     * @param array           &$headers
     */
    private function prepareHeaders($name, StreamInterface $stream, $filename, array &$headers)
    {
        $hasFilename = $filename === '0' || $filename;

        // Set a default content-disposition header if one was not provided
        if (!$this->hasHeader($headers, 'content-disposition')) {
            $headers['Content-Disposition'] = sprintf('form-data; name="%s"', $name);
            if ($hasFilename) {
                $headers['Content-Disposition'] .= sprintf('; filename="%s"', $this->basename($filename));
            }
        }

        // Set a default content-length header if one was not provided
        if (!$this->hasHeader($headers, 'content-length') && ($length = $stream->getSize())) {
            $headers['Content-Length'] = (string) $length;
        }

        // Set a default Content-Type if one was not provided
        if (!$this->hasHeader($headers, 'content-type') && $hasFilename && ($type = $this->getMimetypeHelper()->getMimetypeFromFilename(
            $filename
        ))) {
            $headers['Content-Type'] = $type;
        }
    }

    /**
     * Get the headers formatted for the HTTP message.
     *
     * @return string
     */
    private function getHeaders(array $headers)
    {
        $str = '';
        foreach ($headers as $key => $value) {
            $str .= sprintf("%s: %s\r\n", $key, $value);
        }

        return $str;
    }

    /**
     * Check if header exist.
     *
     * @param string $key     case insensitive
     * @return bool
     */
    private function hasHeader(array $headers, $key)
    {
        $lowercaseHeader = strtolower($key);
        foreach ($headers as $k => $v) {
            if (strtolower($k) === $lowercaseHeader) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the boundary that separates the streams.
     *
     * @return string
     */
    public function getBoundary()
    {
        if ($this->boundary === null) {
            $this->boundary = uniqid('', true);
        }

        return $this->boundary;
    }

    /**
     * @param string $boundary
     *
     * @return MultipartStreamBuilder
     */
    public function setBoundary($boundary)
    {
        $this->boundary = $boundary;

        return $this;
    }

    /**
     * @return MimetypeHelper
     */
    private function getMimetypeHelper()
    {
        if ($this->mimetypeHelper === null) {
            $this->mimetypeHelper = new ApacheMimetypeHelper();
        }

        return $this->mimetypeHelper;
    }

    /**
     * If you have custom file extension you may overwrite the default MimetypeHelper with your own.
     *
     * @return MultipartStreamBuilder
     */
    public function setMimetypeHelper(MimetypeHelper $mimetypeHelper)
    {
        $this->mimetypeHelper = $mimetypeHelper;

        return $this;
    }

    /**
     * Reset and clear all stored data. This allows you to use builder for a subsequent request.
     *
     * @return MultipartStreamBuilder
     */
    public function reset()
    {
        $this->data = [];
        $this->boundary = null;

        return $this;
    }

    /**
     * Gets the filename from a given path.
     *
     * PHP's basename() does not properly support streams or filenames beginning with a non-US-ASCII character.
     *
     * @author Drupal 8.2
     *
     * @param string $path
     *
     * @return string
     */
    private function basename($path)
    {
        $separators = '/';
        if (DIRECTORY_SEPARATOR != '/') {
            // For Windows OS add special separator.
            $separators .= DIRECTORY_SEPARATOR;
        }

        // Remove right-most slashes when $path points to directory.
        $path = rtrim($path, $separators);

        // Returns the trailing part of the $path starting after one of the directory separators.
        $filename = preg_match('@[^' . preg_quote($separators, '@') . ']+$@', $path, $matches) ? $matches[0] : '';

        return $filename;
    }
}
