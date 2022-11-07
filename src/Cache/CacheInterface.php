<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Cache;

interface CacheInterface
{
    /**
     * @param string $key Key of the cached resource
     *
     * @return array|null Return the cached resource, null if the resource was not found.
     */
    public function get(string $key): ?array;

    /**
     * @param string $key   Key of the cached resource
     * @param mixed  $value The cached resource
     */
    public function set(string $key, mixed $value): void;
}
