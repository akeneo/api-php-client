<?php

namespace Akeneo\Pim\Api;

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
     * @throws HttpException
     *
     * @return int returns either http code 201 if the resource has been created or 204 if it has been updated
     */
    public function upsert($code, array $data = []);
}
