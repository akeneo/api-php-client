<?php
declare(strict_types=1);

namespace spec\Akeneo\Pim\ApiClient\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApi;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\CreatableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\DeletableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceInterface;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AppCatalogApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(AppCatalogApi::class);
        $this->shouldImplement(AppCatalogApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(DeletableResourceInterface::class);
    }

    function it_returns_a_list_of_catalogs_with_default_parameters(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(
                AppCatalogApi::APP_CATALOGS_URI,
                [],
                100,
                false,
                []
            )
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_catalogs_with_a_limit(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page,
        ResourceCursorFactoryInterface $cursorFactory,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(AppCatalogApi::APP_CATALOGS_URI, [], 2, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(2, $page)->willReturn($cursor);

        $this->all(2, [])->shouldReturn($cursor);
    }

    function it_returns_a_catalog(ResourceClientInterface $resourceClient)
    {
        $catalogId = 'a_catalog_id';
        $catalog = [
            'id' => $catalogId,
            'name' => 'A catalog name',
            'enabled' => true,
        ];

        $resourceClient
            ->getResource(
                AppCatalogApi::APP_CATALOG_URI,
                [$catalogId]
            )
            ->willReturn($catalog);

        $this->get($catalogId)->shouldReturn($catalog);
    }

    function it_creates_a_catalog(ResourceClientInterface $resourceClient)
    {
        $catalogName = 'A catalog name';

        $expectedCatalog = [
            'id' => '12351d98-200e-4bbc-aa19-7fdda1bd14f2',
            'name' => $catalogName,
            'enabled' => false,
        ];

        $resourceClient
            ->createAndReturnResource(
                AppCatalogApi::APP_CATALOGS_URI,
                [],
                [
                    'name' => $catalogName,
                ]
            )
            ->willReturn($expectedCatalog);

        $this->create(['name' => $catalogName])->shouldReturn($expectedCatalog);

    }

    function it_upserts_a_catalog(ResourceClientInterface $resourceClient)
    {
        $catalogId = 'a_catalog_id';
        $catalogName = 'A catalog name';

        $expectedCatalog = [
            'id' => $catalogId,
            'name' => $catalogName,
            'enabled' => false,
        ];

        $resourceClient
            ->upsertAndReturnResource(
                AppCatalogApi::APP_CATALOG_URI,
                [$catalogId],
                ['name' => $catalogName]
            )
            ->willReturn($expectedCatalog);

        $this->upsert($catalogId, ['name' => $catalogName])->shouldReturn($expectedCatalog);
    }

    function it_deletes_a_catalog(ResourceClientInterface $resourceClient)
    {
        $catalogId = 'a_catalog_id';

        $resourceClient
            ->deleteResource(AppCatalogApi::APP_CATALOG_URI, [$catalogId])
            ->willReturn(HttpClient::HTTP_NO_CONTENT);

        $this->delete($catalogId)->shouldReturn(HttpClient::HTTP_NO_CONTENT);
    }
}
