<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ProductUuidApi implements ProductUuidApiInterface
{
    public const PRODUCTS_UUID_URI = 'api/rest/v1/products-uuid';
    public const PRODUCT_UUID_URI = 'api/rest/v1/products-uuid/%s';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
    }
    public function listPerPage(int $limit = 100, bool $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(static::PRODUCTS_UUID_URI, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    public function all(int $pageSize = 100, array $queryParameters = []): ResourceCursorInterface
    {
        $queryParameters['pagination_type'] = 'search_after';

        $firstPage = $this->listPerPage($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    public function get(string $uuid, array $queryParameters = []): array
    {
        return $this->resourceClient->getResource(static::PRODUCT_UUID_URI, [$uuid], $queryParameters);
    }

    public function create(string $uuid, array $data = []): int
    {
        if (array_key_exists('uuid', $data)) {
            throw new InvalidArgumentException('The parameter "uuid" should not be defined in the data parameter');
        }

        $data['uuid'] = $uuid;

        return $this->resourceClient->createResource(static::PRODUCTS_UUID_URI, [], $data);
    }

    public function upsert(string $uuid, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::PRODUCT_UUID_URI, [$uuid], $data);
    }

    public function delete(string $uuid): int
    {
        return $this->resourceClient->deleteResource(static::PRODUCT_UUID_URI, [$uuid]);
    }

    public function upsertList($resources): \Traversable
    {
        return $this->resourceClient->upsertStreamResourceList(static::PRODUCTS_UUID_URI, [], $resources);
    }
}
