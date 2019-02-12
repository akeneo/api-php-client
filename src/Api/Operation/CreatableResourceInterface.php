<?php

namespace Akeneo\Pim\ApiClient\Api\Operation;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;

/**
 * API that can create a resource.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CreatableResourceInterface
{
    /**
     * Creates a resource.
     *
     * @param string $code code of the resource to create
     * @param array  $data data of the resource to create
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the parameter "code" is defined in the data parameter.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     */
    public function create(string $code, array $data = []): int;
}
