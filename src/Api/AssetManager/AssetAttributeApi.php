<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;

class AssetAttributeApi implements AssetAttributeApiInterface
{
    const ASSET_ATTRIBUTE_URI = 'api/rest/v1/asset-families/%s/attributes/%s';
    const ASSET_ATTRIBUTES_URI = 'api/rest/v1/asset-families/%s/attributes';

    /** @var ResourceClientInterface */
    private $resourceClient;

    public function __construct(ResourceClientInterface $resourceClient)
    {
        $this->resourceClient = $resourceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $assetFamilyCode, string $attributeCode): array
    {
        return $this->resourceClient->getResource(static::ASSET_ATTRIBUTE_URI, [$assetFamilyCode, $attributeCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $assetFamilyCode, array $queryParameters = []): array
    {
        return $this->resourceClient->getResource(static::ASSET_ATTRIBUTES_URI, [$assetFamilyCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $assetFamilyCode, string $attributeCode, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::ASSET_ATTRIBUTE_URI, [$assetFamilyCode, $attributeCode], $data);
    }
}
