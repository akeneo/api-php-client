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
        $this->shouldImplement(CreatableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceInterface::class);
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
                10,
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
        $aCatalogId = 'a_catalog_id';
        $aCatalog = [
            'id' => $aCatalogId,
            'name' => 'A catalog name',
            'enabled' => true,
        ];

        $resourceClient
            ->getResource(
                AppCatalogApi::APP_CATALOG_URI,
                [$aCatalogId]
            )
            ->willReturn($aCatalog);

        $this->get($aCatalogId)->shouldReturn($aCatalog);
    }

    function it_creates_a_catalog(ResourceClientInterface $resourceClient)
    {
        $aCatalogId = 'a_catalog_id';
        $aCatalogName = 'A catalog name';

        $resourceClient
            ->createResource(
                AppCatalogApi::APP_CATALOGS_URI,
                [],
                [
                    'id' => $aCatalogId,
                    'name' => $aCatalogName,
                ]
            )
            ->willReturn(HttpClient::HTTP_CREATED);

        $this->create($aCatalogId, ['name' => $aCatalogName])->shouldReturn(HttpClient::HTTP_CREATED);
    }

    function it_throws_an_exception_if_provided_catalog_id_is_in_data_when_creating_a_catalog()
    {
        $aCatalogId = 'a_catalog_id';
        $aCatalogName = 'A catalog name';

        $this
            ->shouldThrow(new InvalidArgumentException(AppCatalogApi::ID_MUST_BE_DEFINED_EXCEPTION_MESSAGE))
            ->during('create', [$aCatalogId, ['id' => $aCatalogId, 'name' => $aCatalogName]]);
    }

    function it_upserts_a_catalog(ResourceClientInterface $resourceClient)
    {
        $aCatalogId = 'a_catalog_id';
        $aCatalogName = 'A catalog name';

        $resourceClient
            ->upsertResource(
                AppCatalogApi::APP_CATALOG_URI,
                [$aCatalogId],
                ['id' => $aCatalogId, 'name' => $aCatalogName]
            )
            ->willReturn(HttpClient::HTTP_OK);

        $this->upsert($aCatalogId, ['id' => $aCatalogId, 'name' => $aCatalogName])
            ->shouldReturn(HttpClient::HTTP_OK);
    }

    function it_deletes_a_catalog(ResourceClientInterface $resourceClient)
    {
        $aCatalogId = 'a_catalog_id';

        $resourceClient
            ->deleteResource(AppCatalogApi::APP_CATALOG_URI, [$aCatalogId])
            ->willReturn(HttpClient::HTTP_NO_CONTENT);

        $this->delete($aCatalogId)->shouldReturn(HttpClient::HTTP_NO_CONTENT);
    }
}
