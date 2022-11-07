<?php

namespace Akeneo\Pim\ApiClient\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Http client to send a request without any authentication.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpClient implements HttpClientInterface
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    protected HttpExceptionHandler $httpExceptionHandler;

    public function __construct(
        protected ClientInterface $httpClient,
        protected RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private Options $options
    ) {
        $this->httpExceptionHandler = new HttpExceptionHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(string $httpMethod, $uri, array $headers = [], $body = null): ResponseInterface
    {
        $request = $this->requestFactory->createRequest($httpMethod, $uri);

        if (null !== $body && is_string($body)) {
            $request = $request->withBody($this->streamFactory->createStream($body));
        }
        if (null !== $body && $body instanceof StreamInterface) {
            $request = $request->withBody($body);
        }

        foreach ($headers as $header => $content) {
            $request = $request->withHeader($header, $content);
        }

        if ($this->options->hasHeaders()) {
            foreach ($this->options->getHeaders() as $header => $content) {
                $request = $request->withHeader($header, $content);
            }
        }

        $response = $this->httpClient->sendRequest($request);

        return $this->httpExceptionHandler->transformResponseToException($request, $response);
    }
}
