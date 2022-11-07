<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Client;

use Akeneo\Pim\ApiClient\Cache\CacheInterface;
use Akeneo\Pim\ApiClient\Client\CachedResourceClient;
use Akeneo\Pim\ApiClient\Client\ResourceClient;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;

class CachedResourceClientTest extends ApiTestCase
{
    public function test_get_cached_resource(): void
    {
        $resourceClient = $this->createMock(ResourceClient::class);
        $mockCache = $this->createMock(CacheInterface::class);

        $uri = 'uri';
        $uriParameters = ['uriParameter'];

        $cacheKey = md5($uri . implode('', $uriParameters));

        $mockCache
            ->expects(self::exactly(2))
            ->method('get')
            ->with($cacheKey)
            ->willReturnOnConsecutiveCalls(null, ['cachedValue']);

        $resourceClient
            ->expects(self::once())
            ->method('getResource')
            ->with($uri, $uriParameters)->willReturn(['resource']);

        $mockCache
            ->expects(self::once())
            ->method('set')
            ->with($cacheKey, ['resource']);

        $cachedResourceClient = new CachedResourceClient($resourceClient, $mockCache);

        self::assertSame(['resource'], $cachedResourceClient->getResource($uri, $uriParameters));
        self::assertSame(['cachedValue'], $cachedResourceClient->getResource($uri, $uriParameters));
    }
}
