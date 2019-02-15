<?php

namespace Akeneo\Pim\ApiClient\Api\Operation;

use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * API that can delete a resource
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface DeletableResourceInterface
{
    /**
     * Deletes a resource.
     *
     * @param string $code code of the resource to delete
     *
     * @throws HttpException
     *
     * @return int Status code 204 indicating that the resource has been well deleted.
     */
    public function delete(string $code): int;
}
