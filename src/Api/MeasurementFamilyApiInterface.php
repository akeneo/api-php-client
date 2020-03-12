<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * API to manage the measurement families.
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface MeasurementFamilyApiInterface
{
    /**
     * Gets a cursor to iterate over all the measurement families
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Updates or creates several resources.
     *
     * @param array $resources array object containing the measurement families to create or update
     *
     * @throws HttpException
     *
     * @return array returns an array, each entry corresponding to the response of the upserted measurement families
     */
    public function upsertList($resources): array;
}
