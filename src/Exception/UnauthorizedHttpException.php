<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when it is an unauthorized request exception (401).
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UnauthorizedHttpException extends ClientErrorHttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#unauthorized-exception)';
    }
}
