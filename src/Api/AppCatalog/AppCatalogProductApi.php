<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AppCatalogProductApi implements AppCatalogProductApiInterface
{
    public const APP_CATALOG_PRODUCT_URI = '/api/rest/v1/catalogs/%s/product-uuids';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $catalogId, int $limit = 100, array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::APP_CATALOG_PRODUCT_URI,
            [$catalogId],
            $limit,
            false,
            $queryParameters
        );

        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor(null, $firstPage);
    }
}
