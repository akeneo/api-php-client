<?php

namespace Akeneo\Pim\HttpClient;

use Akeneo\Pim\Exception\BadRequestHttpException;
use Akeneo\Pim\Exception\ClientErrorHttpException;
use Akeneo\Pim\Exception\ServerErrorHttpException;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Psr\Http\Message\ResponseInterface;

/**
 * Transform response to an error if possible.
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
     * @param ResponseInterface $response     Response of the call
     *
     * @throws BadRequestHttpException           If response status code is a 400
     * @throws UnprocessableEntityHttpException  If response status code is a 422
     * @throws ClientErrorHttpException          If response status code is a 4xx
     * @throws ServerErrorHttpException          If response status code is a 5xx
     *
     * @return ResponseInterface
     */
    public function transformResponseToException(ResponseInterface $response)
    {
        if ($response->getStatusCode() === 400) {
            throw new BadRequestHttpException($response->getReasonPhrase(), $response);
        }

        if ($response->getStatusCode() === 401) {
            throw new UnauthorizedHttpException($response->getReasonPhrase(), $response);
        }

        if ($response->getStatusCode() === 422) {
            throw new UnprocessableEntityHttpException($response->getReasonPhrase(), $response);
        }

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            throw new ClientErrorHttpException($response->getReasonPhrase(), $response);
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerErrorHttpException($response->getReasonPhrase(), $response);
        }

        return $response;
    }

}
