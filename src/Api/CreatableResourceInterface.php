<?php

namespace Akeneo\Pim\Api;

/**
 * API that can create a resource
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
     */
    public function create($code, array $data = []);
}
