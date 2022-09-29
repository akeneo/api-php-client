<?php
declare(strict_types=1);

namespace spec\Akeneo\Pim\ApiClient\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApi;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AppCatalogProductApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AppCatalogProductApi::class);
        $this->shouldImplement(AppCatalogProductApiInterface::class);
    }

    function it_returns_a_list_of_catalog_product_uuids_with_default_parameters(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page,
        ResourceCursorFactoryInterface $cursorFactory,
        ResourceCursorInterface $cursor
    ) {
        $aCatalogId = 'a_catalog_id';

        $resourceClient
            ->getResources(
                AppCatalogProductApi::APP_CATALOG_PRODUCT_URI,
                [$aCatalogId],
                100,
                false,
                []
            )
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);
        $cursorFactory->createCursor(null, $page)->willReturn($cursor);

        $this->all($aCatalogId)->shouldReturn($cursor);
    }

    function it_returns_a_list_of_catalog_product_uuids_with_a_limit(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page,
        ResourceCursorFactoryInterface $cursorFactory,
        ResourceCursorInterface $cursor
    ) {
        $aCatalogId = 'a_catalog_id';

        $resourceClient
            ->getResources(
                AppCatalogProductApi::APP_CATALOG_PRODUCT_URI,
                [$aCatalogId],
                2,
                false,
                []
            )
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);
        $cursorFactory->createCursor(null, $page)->willReturn($cursor);

        $this->all($aCatalogId, 2)->shouldReturn($cursor);
    }
}
