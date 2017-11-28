<?php

namespace Akeneo\Pim\ApiClient\Client;

use Http\Client\HttpClient as Client;
use Http\Message\RequestFactory;

/**
 * Http client to send a request without any authentication.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpClient implements HttpClientInterface
{
    /** @var Client */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var HttpExceptionHandler */
    protected $httpExceptionHandler;

    /**
     * @param Client         $httpClient
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        Client $httpClient,
        RequestFactory $requestFactory
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->httpExceptionHandler = new HttpExceptionHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest($httpMethod, $uri, array $headers = [], $body = null)
    {
        $request = $this->requestFactory->createRequest($httpMethod, $uri, $headers, $body);
        $response = $this->httpClient->sendRequest($request);
        $response = $this->httpExceptionHandler->transformResponseToException($request, $response);

        return $response;
    }
}
