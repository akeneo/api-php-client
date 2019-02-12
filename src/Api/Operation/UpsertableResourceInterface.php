<?php

namespace Akeneo\Pim\ApiClient\Api\Operation;

use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * API that can "upsert" a resource.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface UpsertableResourceInterface
{
    /**
     * Creates a resource if it does not exist yet, otherwise updates partially the resource.
     *
     * @param string $code code of the resource to create or update
     * @param array  $data data of the resource to create or update
     *
     * @throws HttpException If the request failed.
     *
     * @return int Status code 201 indicating that the resource has been well created.
     *             Status code 204 indicating that the resource has been well updated.
     */
    public function upsert(string $code, array $data = []): int;
}
