<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Exception\HttpException;

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
     * @throws HttpException
     *
     * @return int Status code 201 indicating that the resource has been well created.
     */
    public function create($code, array $data = []);
}
