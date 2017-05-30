<?php

namespace Akeneo\Pim\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Aims to read the response of an upsert of a list of resources.
 *
 * It reads the content of the response line by line, when iterating through an instance of this class.
 * Each line represents the JSON response of an upserted resource
 *
 * This iterator automatically decodes the JSON before returning the line.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpsertListResourcesResponse implements \Iterator
{
    /** @var StreamInterface */
    protected $bodyStream;

    /** @var int */
    protected $lineNumber = 1;

    /** @var string */
    protected $line;

    /**
     * @param StreamInterface $bodyStream
     */
    public function __construct(StreamInterface $bodyStream)
    {
        $this->bodyStream = $bodyStream;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $line = $this->getNextLine($this->bodyStream);
        $this->lineNumber++;

        return json_decode($line);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->lineNumber;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->bodyStream->isReadable() && !$this->bodyStream->eof();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->bodyStream->rewind();
        $this->lineNumber = 0;
    }

    /**
     * Gets the next line of the stream.
     *
     * @param StreamInterface $stream
     *
     * @return string
     */
    protected function getNextLine(StreamInterface $stream)
    {
        $line  = '';
        $isEol = false;
        while (!$stream->eof() && !$isEol) {
            $character = $stream->read(1);
            $isEol = PHP_EOL === $character;

            if (!$isEol) {
                $line .= $character;
            }
        }

        return $line;
    }
}
