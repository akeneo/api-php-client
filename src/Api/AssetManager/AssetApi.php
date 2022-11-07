<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

class AssetApi implements AssetApiInterface
{
    public const ASSET_URI = 'api/rest/v1/asset-families/%s/assets/%s';
    public const ASSETS_URI = 'api/rest/v1/asset-families/%s/assets';

    public function __construct(
        private ResourceClientInterface $resourceClient,
        private PageFactoryInterface $pageFactory,
        private ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $assetFamilyCode, string $assetCode): array
    {
        return $this->resourceClient->getResource(static::ASSET_URI, [$assetFamilyCode, $assetCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $assetFamilyCode, array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::ASSETS_URI,
            [$assetFamilyCode],
            null,
            null,
            $queryParameters
        );

        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor(null, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $assetFamilyCode, string $assetCode, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::ASSET_URI, [$assetFamilyCode, $assetCode], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList(string $assetFamilyCode, array $assets): array
    {
        return $this->resourceClient->upsertJsonResourceList(static::ASSETS_URI, [$assetFamilyCode], $assets);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $assetFamilyCode, string $assetCode): int
    {
        return $this->resourceClient->deleteResource(static::ASSET_URI, [$assetFamilyCode, $assetCode]);
    }
}
