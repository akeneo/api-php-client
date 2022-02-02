<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * @copyright 2022 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ForbiddenHttpException extends ClientErrorHttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#forbidden-exception)';
    }
}
