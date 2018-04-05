<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown when the HTTP response is a redirection (3xx).
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RedirectionHttpException extends ClientErrorHttpException
{
}
