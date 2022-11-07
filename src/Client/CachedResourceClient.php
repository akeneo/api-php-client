<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Cache\CacheInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class CachedResourceClient implements ResourceClientInterface
{
    public function __construct(
        private ResourceClientInterface $resourceClient,
        private CacheInterface $cache
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getResource(string $uri, array $uriParameters = [], array $queryParameters = []): array
    {
        $cacheKey = md5($uri . implode('', $uriParameters));

        if ($cachedItem = $this->cache->get($cacheKey)) {
            return $cachedItem;
        }

        $resource = $this->resourceClient->getResource($uri, $uriParameters, $queryParameters);
        $this->cache->set($cacheKey, $resource);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(
        string $uri,
        array $uriParameters = [],
        ?int $limit = 100,
        ?bool $withCount = false,
        array $queryParameters = []
    ): array {
        return $this->resourceClient->getResources($uri, $uriParameters, $limit, $withCount, $queryParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createResource(string $uri, array $uriParameters = [], array $body = []): int
    {
        return $this->resourceClient->createResource($uri, $uriParameters, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function createMultipartResource(string $uri, array $uriParameters = [], array $requestParts = []): ResponseInterface
    {
        return $this->resourceClient->createMultipartResource($uri, $uriParameters, $requestParts);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertResource(string $uri, array $uriParameters = [], array $body = []): int
    {
        return $this->resourceClient->upsertResource($uri, $uriParameters, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertStreamResourceList(string $uri, array $uriParameters = [], $resources = []): \Traversable
    {
        return $this->resourceClient->upsertStreamResourceList($uri, $uriParameters, $resources);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertJsonResourceList(string $uri, array $uriParameters = [], array $resources = []): array
    {
        return $this->resourceClient->upsertJsonResourceList($uri, $uriParameters, $resources);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteResource(string $uri, array $uriParameters = []): int
    {
        return $this->resourceClient->deleteResource($uri, $uriParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamedResource(string $uri, array $uriParameters = []): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource($uri, $uriParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createAndReturnResource(string $uri, array $uriParameters = [], array $body = []): array
    {
        return $this->resourceClient->createAndReturnResource($uri, $uriParameters, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertAndReturnResource(string $uri, array $uriParameters = [], array $body = []): array
    {
        return $this->resourceClient->upsertAndReturnResource($uri, $uriParameters, $body);
    }
}
