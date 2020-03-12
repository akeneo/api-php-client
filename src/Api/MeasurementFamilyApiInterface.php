<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;

/**
 * API to manage the measurement families.
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface MeasurementFamilyApiInterface extends ListableResourceInterface
{
    /**
     * Updates or creates several resources.
     *
     * @param array|StreamInterface $resources array or StreamInterface object containing the resources to create or update
     *
     * @throws HttpException
     *
     * @return array returns an array, each entry corresponding to the response of the upserted resource
     */
    public function upsertList($resources): array;
}
