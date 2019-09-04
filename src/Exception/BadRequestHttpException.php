<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when it is a bad request exception (400).
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class BadRequestHttpException extends ClientErrorHttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#bad-request-exception)';
    }
}
