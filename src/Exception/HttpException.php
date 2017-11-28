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

    /**
     * @param string            $message  message of the exception
     * @param RequestInterface  $request  failing request
     * @param ResponseInterface $response response of the failing request
     * @param \Exception|null   $previous previous exception
     */
    public function __construct($message, RequestInterface $request, ResponseInterface $response, \Exception $previous = null)
    {
        parent::__construct($message, $response->getStatusCode(), $previous);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Returns the request.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
