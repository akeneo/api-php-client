<?php

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\BadRequestHttpException;
use Akeneo\Pim\ApiClient\Exception\ClientErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\ForbiddenHttpException;
use Akeneo\Pim\ApiClient\Exception\MethodNotAllowedHttpException;
use Akeneo\Pim\ApiClient\Exception\NotAcceptableHttpException;
use Akeneo\Pim\ApiClient\Exception\NotFoundHttpException;
use Akeneo\Pim\ApiClient\Exception\RedirectionHttpException;
use Akeneo\Pim\ApiClient\Exception\ServerErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\TooManyRequestsHttpException;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\Exception\UnsupportedMediaTypeHttpException;
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
     * @throws UnauthorizedHttpException         If response status code is a 401
     * @throws NotFoundHttpException             If response status code is a 404
     * @throws UnprocessableEntityHttpException  If response status code is a 422
     * @throws ClientErrorHttpException          If response status code is a 4xx
     * @throws ServerErrorHttpException          If response status code is a 5xx
     *
     * @throws BadRequestHttpException           If response status code is a 400
     */
    public function transformResponseToException(
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($this->isSuccessStatusCode($response->getStatusCode())) {
            return $response;
        }

        if ($this->isRedirectionStatusCode($response->getStatusCode())) {
            throw new RedirectionHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_BAD_REQUEST === $response->getStatusCode()) {
            throw new BadRequestHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
            throw new UnauthorizedHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_FORBIDDEN === $response->getStatusCode()) {
            throw new ForbiddenHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_NOT_FOUND === $response->getStatusCode()) {
            throw new NotFoundHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_METHOD_NOT_ALLOWED === $response->getStatusCode()) {
            throw new MethodNotAllowedHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_NOT_ACCEPTABLE === $response->getStatusCode()) {
            throw new NotAcceptableHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_UNSUPPORTED_MEDIA_TYPE === $response->getStatusCode()) {
            throw new UnsupportedMediaTypeHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_UNPROCESSABLE_ENTITY === $response->getStatusCode()) {
            throw new UnprocessableEntityHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_TOO_MANY_REQUESTS === $response->getStatusCode()) {
            throw new TooManyRequestsHttpException($response->getBody()->getContents(), $request, $response);
        }

        if ($this->isApiClientErrorStatusCode($response->getStatusCode())) {
            throw new ClientErrorHttpException($this->getResponseMessage($response), $request, $response);
        }

        throw new ServerErrorHttpException($this->getResponseMessage($response), $request, $response);
    }

    /**
     * Returns the response message, or the reason phrase if there is none.
     */
    protected function getResponseMessage(ResponseInterface $response): string
    {
        $responseBody = $response->getBody();

        $responseBody->rewind();
        $decodedBody = json_decode($responseBody->getContents(), true);
        $responseBody->rewind();

        return isset($decodedBody['message']) ? $decodedBody['message'] : $response->getReasonPhrase();
    }

    private function isSuccessStatusCode(int $statusCode): bool
    {
        return in_array($statusCode, [
            HttpClient::HTTP_OK,
            HttpClient::HTTP_CREATED,
            HttpClient::HTTP_NO_CONTENT,
        ]);
    }

    private function isApiClientErrorStatusCode(int $statusCode): bool
    {
        return $statusCode >= 400 && $statusCode < 500;
    }

    private function isRedirectionStatusCode(int $statusCode): bool
    {
        return $statusCode >= 300 && $statusCode < 400;
    }
}
