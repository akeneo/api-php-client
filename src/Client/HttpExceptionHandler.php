<?php

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\BadRequestHttpException;
use Akeneo\Pim\ApiClient\Exception\ClientErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\NotFoundHttpException;
use Akeneo\Pim\ApiClient\Exception\RedirectionHttpException;
use Akeneo\Pim\ApiClient\Exception\ServerErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * It aims to throw exception thanks to the the response's HTTP status code if the request has failed.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpExceptionHandler
{
    /**
     * Transforms response to an exception if possible.
     *
     * @param RequestInterface  $request  Request of the call
     * @param ResponseInterface $response Response of the call
     *
     * @throws BadRequestHttpException           If response status code is a 400
     * @throws UnauthorizedHttpException         If response status code is a 401
     * @throws NotFoundHttpException             If response status code is a 404
     * @throws UnprocessableEntityHttpException  If response status code is a 422
     * @throws ClientErrorHttpException          If response status code is a 4xx
     * @throws ServerErrorHttpException          If response status code is a 5xx
     *
     * @return ResponseInterface
     */
    public function transformResponseToException(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
            throw new RedirectionHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (400 === $response->getStatusCode()) {
            throw new BadRequestHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (401 === $response->getStatusCode()) {
            throw new UnauthorizedHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (404 === $response->getStatusCode()) {
            throw new NotFoundHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (422 === $response->getStatusCode()) {
            throw new UnprocessableEntityHttpException($this->getResponseMessage($response), $request, $response);
        }

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            throw new ClientErrorHttpException($this->getResponseMessage($response), $request, $response);
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerErrorHttpException($this->getResponseMessage($response), $request, $response);
        }

        return $response;
    }

    /**
     * Returns the response message, or the reason phrase if there is none.
     *
     * @param ResponseInterface $response
     *
     * @return string
     */
    protected function getResponseMessage(ResponseInterface $response): string
    {
        $responseBody = $response->getBody();

        $responseBody->rewind();
        $decodedBody = json_decode($responseBody->getContents(), true);
        $responseBody->rewind();

        return isset($decodedBody['message']) ? $decodedBody['message'] : $response->getReasonPhrase();
    }
}
