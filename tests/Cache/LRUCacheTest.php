<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Cache;

use Akeneo\Pim\ApiClient\Cache\LRUCache;
use PHPUnit\Framework\TestCase;

class LRUCacheTest extends TestCase
{
    public function testLimitsSize(): void
    {
        $cache = new LRUCache(3);
        $cache->set('a', [1]);
        $cache->set('b', [2]);
        $cache->set('c', [3]);
        $cache->set('d', [4]);
        $cache->set('e', [5]);
        $this->assertNull($cache->get('a'));
        $this->assertNull($cache->get('b'));
        $this->assertSame([3], $cache->get('c'));
        $this->assertSame([4], $cache->get('d'));
        $this->assertSame([5], $cache->get('e'));
    }

    public function testRemovesLru(): void
    {
        $cache = new LRUCache(3);
        $cache->set('a', [1]);
        $cache->set('b', [2]);
        $cache->set('c', [3]);
        $cache->get('a'); // Puts a back on the end
        $cache->set('d', [4]);
        $cache->set('e', [5]);
        $this->assertNull($cache->get('b'));
        $this->assertNull($cache->get('c'));
        $this->assertSame([1], $cache->get('a'));
        $this->assertSame([4], $cache->get('d'));
        $this->assertSame([5], $cache->get('e'));
    }
}
