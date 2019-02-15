<?php

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Http client interface aims to send a request.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface HttpClientInterface
{
    /**
     * Sends a request.
     *
     * @param string                      $httpMethod HTTP method to use
     * @param string|UriInterface         $uri        URI of the request
     * @param array                       $headers    headers of the request
     * @param string|StreamInterface|null $body       body of the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResponseInterface
     */
    public function sendRequest(string $httpMethod, $uri, array $headers = [], $body = null): ResponseInterface;
}
