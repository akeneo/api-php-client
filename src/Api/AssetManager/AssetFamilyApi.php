<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

class AssetFamilyApi implements AssetFamilyApiInterface
{
    public const ASSET_FAMILY_URI = 'api/rest/v1/asset-families/%s';
    public const ASSET_FAMILIES_URI = 'api/rest/v1/asset-families';

    public function __construct(
        private ResourceClientInterface $resourceClient,
        private PageFactoryInterface $pageFactory,
        private ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $code): array
    {
        return $this->resourceClient->getResource(static::ASSET_FAMILY_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::ASSET_FAMILIES_URI,
            [],
            null,
            false,
            $queryParameters
        );

        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor(null, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $code, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::ASSET_FAMILY_URI, [$code], $data);
    }
}
