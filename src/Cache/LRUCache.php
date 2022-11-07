<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Cache;

class LRUCache implements CacheInterface
{
    private array $items = [];

    /**
     * @param int $maxItems Maximum number of allowed cache items.
     */
    public function __construct(
        private int $maxItems = 100
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ?array
    {
        if (!isset($this->items[$key])) {
            return null;
        }

        $entry = $this->items[$key];

        unset($this->items[$key]);
        $this->items[$key] = $entry;

        return $entry;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): void
    {
        $this->items[$key] = $value;

        $diff = count($this->items) - $this->maxItems;

        if ($diff <= 0) {
            return;
        }
        for ($i = 0; $i < $diff; $i++) {
            unset($this->items[array_key_first($this->items)]);
            next($this->items);
        }
    }
}
