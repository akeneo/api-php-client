<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when it is a client error code (4xx).
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ClientErrorHttpException extends HttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#client-exception)';
    }
}
