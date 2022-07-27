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
    const APP_CATALOG_PRODUCT_URI = '/api/rest/v1/catalogs/%s/product-uuids';

    protected ResourceClientInterface $resourceClient;
    protected PageFactoryInterface $pageFactory;
    protected ResourceCursorFactoryInterface $cursorFactory;

    public function __construct(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->resourceClient = $resourceClient;
        $this->pageFactory = $pageFactory;
        $this->cursorFactory = $cursorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $catalogId, int $limit = 10, array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::APP_CATALOG_PRODUCT_URI,
            [$catalogId],
            $limit,
            false,
            $queryParameters
        );

        //var_dump($data);

        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor(null, $firstPage);
    }
}
