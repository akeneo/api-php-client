<?php

namespace Akeneo\Pim\ApiClient\Api\Operation;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Psr\Http\Message\StreamInterface;

/**
 * API that can "upsert" a list of resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface UpsertableResourceListInterface
{
    /**
     * Updates or creates several resources.
     *
     * @param array|StreamInterface $resources array or StreamInterface object containing the resources to create or update
     *
     * @throws HttpException
     *
     * @return \Traversable returns an iterable object, each entry corresponding to the response of the upserted resource
     */
    public function upsertList($resources): \Traversable;
}
