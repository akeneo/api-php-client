<?php

namespace Akeneo\Pim\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Http exception thrown when a response failed.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpException extends \RuntimeException
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param string            $message
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     */
    public function __construct($message, ResponseInterface $response, \Exception $previous = null) {
        parent::__construct($message, $response->getStatusCode(), $previous);

        $this->response = $response;
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

    /**
     * Returns the HTTP status code of the response.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Returns the body's content of the response.
     *
     * @return string
     */
    public function getResponseBody()
    {
        return $this->response->getBody()->getContents();
    }
}
