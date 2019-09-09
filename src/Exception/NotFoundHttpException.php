<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when a resource has not been found (404).
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class NotFoundHttpException extends ClientErrorHttpException
{
    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#not-found-exception)';
    }
}
