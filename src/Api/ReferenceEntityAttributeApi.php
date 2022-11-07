<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceEntityAttributeApi implements ReferenceEntityAttributeApiInterface
{
    public const REFERENCE_ENTITY_ATTRIBUTE_URI = 'api/rest/v1/reference-entities/%s/attributes/%s';
    public const REFERENCE_ENTITY_ATTRIBUTES_URI = 'api/rest/v1/reference-entities/%s/attributes';

    public function __construct(
        private ResourceClientInterface $resourceClient
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $referenceEntityCode, string $attributeCode): array
    {
        return $this->resourceClient->getResource(static::REFERENCE_ENTITY_ATTRIBUTE_URI, [$referenceEntityCode, $attributeCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $referenceEntityCode, array $queryParameters = []): array
    {
        return $this->resourceClient->getResource(static::REFERENCE_ENTITY_ATTRIBUTES_URI, [$referenceEntityCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $referenceEntityCode, string $attributeCode, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::REFERENCE_ENTITY_ATTRIBUTE_URI, [$referenceEntityCode, $attributeCode], $data);
    }
}
