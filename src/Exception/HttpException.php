<?php

namespace Akeneo\Pim\ApiClient\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Http exception thrown when a request failed.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpException extends RuntimeException
{
    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    public function __construct(string $message, RequestInterface $request, ResponseInterface $response, ?\Exception $previous = null)
    {
        $message .= ' ' . $this->getAdditionalInformationMessage();

        parent::__construct($message, $response->getStatusCode(), $previous);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Returns the request.
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Returns the response.
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#http-exception)';
    }
}
