<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when it is a server error code (5xx).
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ServerErrorHttpException extends HttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#server-exception)';
    }
}
