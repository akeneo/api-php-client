<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * Exception thrown if an error which can only be found on runtime occurs in the API client.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
