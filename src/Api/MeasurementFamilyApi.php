<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;

/**
 * API implementation to manage measurement families.
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class MeasurementFamilyApi implements MeasurementFamilyApiInterface
{
    public const MEASUREMENT_FAMILIES_URI = 'api/rest/v1/measurement-families';

    public function __construct(
        protected ResourceClientInterface $resourceClient
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->resourceClient->getResource(static::MEASUREMENT_FAMILIES_URI);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList($resources): array
    {
        return $this->resourceClient->upsertJsonResourceList(static::MEASUREMENT_FAMILIES_URI, [], $resources);
    }
}
