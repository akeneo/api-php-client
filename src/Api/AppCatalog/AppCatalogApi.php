<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AppCatalog;

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
class AppCatalogApi implements AppCatalogApiInterface
{
    public const APP_CATALOGS_URI = 'api/rest/v1/catalogs';
    public const APP_CATALOG_URI = 'api/rest/v1/catalogs/%s';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    public function listPerPage(int $limit = 100, bool $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(static::APP_CATALOGS_URI, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    public function all(int $pageSize = 100, array $queryParameters = []): ResourceCursorInterface
    {
        $firstPage = $this->listPerPage($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    public function create(array $data): array
    {
        return $this->resourceClient->createAndReturnResource(static::APP_CATALOGS_URI, [], $data);
    }

    public function get(string $code): array
    {
        return $this->resourceClient->getResource(static::APP_CATALOG_URI, [$code]);
    }

    public function upsert(string $code, array $data = []): array
    {
        return $this->resourceClient->upsertAndReturnResource(static::APP_CATALOG_URI, [$code], $data);
    }

    public function delete(string $code): int
    {
        return $this->resourceClient->deleteResource(static::APP_CATALOG_URI, [$code]);
    }
}
