<?php

namespace Akeneo\Pim\Exception;

/**
 * Exception thrown when it is the request is unprocessable (422).
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UnprocessableEntityHttpException extends ClientErrorHttpException
{
    /**
     * Returns the errors of the response if there are any
     *
     * @return array
     */
    public function getResponseErrors()
    {
        $decodedBody = json_decode($this->getResponse()->getBody()->getContents(), true);

        return isset($decodedBody['errors']) ? $decodedBody['errors'] : [];
    }
}
