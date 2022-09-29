<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssetCategoryApi;
use Akeneo\Pim\ApiClient\Api\AssetCategoryApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class AssetCategoryApiSpec extends ObjectBehavior
{
    public function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AssetCategoryApi::class);
        $this->shouldImplement(AssetCategoryApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    public function it_gets_an_asset_category($resourceClient)
    {
        $assetCategory = [
            'code' => 'asset_main_catalog',
            'parent' => null,
            'labels' => [
                'en_US' => 'dolor sed perferendis',
            ],
        ];

        $resourceClient->getResource(AssetCategoryApi::ASSET_CATEGORY_URI, ['asset_main_catalog'])->willReturn($assetCategory);

        $this->get('asset_main_catalog')->shouldReturn($assetCategory);
    }

    function it_returns_a_list_of_asset_categories_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetCategoryApi::ASSET_CATEGORIES_URI, [], 100, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_asset_categories_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetCategoryApi::ASSET_CATEGORIES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_asset_categories(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(
                AssetCategoryApi::ASSET_CATEGORIES_URI,
                [],
                10,
                false,
                []
            )
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_asset_categories_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetCategoryApi::ASSET_CATEGORIES_URI, [], 10, true, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_upserts_an_asset_category($resourceClient)
    {
        $resourceClient
            ->upsertResource(AssetCategoryApi::ASSET_CATEGORY_URI, ['asset_main_catalog'], [
                'labels' => [
                    'en_US' => 'Nullam ullamcorper',
                ]
            ])
            ->willReturn(204);

        $this->upsert('asset_main_catalog', [
            'labels' => [
                'en_US' => 'Nullam ullamcorper',
            ]
        ])->shouldReturn(204);
    }

    function it_upserts_a_list_of_asset_categories($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertStreamResourceList(
                AssetCategoryApi::ASSET_CATEGORIES_URI,
                [],
                [
                    ['code' => 'asset_1'],
                    ['code' => 'asset_2'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'asset_1'],
                ['code' => 'asset_2'],
            ])->shouldReturn($response);
    }

    function it_creates_an_asset_category($resourceClient)
    {
        $resourceClient->createResource(AssetCategoryApi::ASSET_CATEGORIES_URI, [], [
            'code' => 'asset_spring',
            'parent' => null,
            'labels' => [
                'en_US' => 'Nullam ullamcorper',
            ],
        ])->willReturn(201);

        $this->create('asset_spring', [
            'parent' => null,
            'labels' => [
                'en_US' => 'Nullam ullamcorper',
            ],
        ])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_an_asset_category()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['asset_spring', [
                'code' => 'asset_spring',
                'parent' => null,
                'labels' => [
                    'en_US' => 'Nullam ullamcorper',
                ],
            ]]);
    }
}
