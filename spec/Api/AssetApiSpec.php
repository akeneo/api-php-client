<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssetApi;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class AssetApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(AssetApi::class);
    }

    function it_gets_an_asset($resourceClient)
    {
        $asset = [
            'code' => 'akeneo_logo',
            'localized' => false,
            'description' => 'Akeneo logo',
            'end_of_use' => null,
            'tags' => [],
            'categories' => ['asset_main_catalog'],
            'variation_files' => [],
            'reference_files' => [],
        ];

        $resourceClient->getResource(AssetApi::ASSET_URI, ['akeneo_logo'])->willReturn($asset);

        $this->get('akeneo_logo')->shouldReturn($asset);
    }

    function it_returns_a_list_of_assets_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetApi::ASSETS_URI, [], 100, false, [])
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);
        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_assets_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetApi::ASSETS_URI, [], 10, true, [])
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);
        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_assets(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(
                AssetApi::ASSETS_URI,
                [],
                10,
                false,
                ['pagination_type' => 'search_after']
            )
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);
        $cursorFactory->createCursor(10, $page)->willReturn($cursor);
        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_assets_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetApi::ASSETS_URI, [], 10, true, ['foo' => 'bar'])
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);
        $this->listPerPage(10, true, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_an_asset($resourceClient)
    {
        $resourceClient->createResource(AssetApi::ASSETS_URI, [], [
            'code' => 'unicorn',
            'localized' => false,
            'description' => 'The wonderful unicorn',
            'end_of_use' => null,
            'tags' => [],
            'categories' => ['asset_main_catalog'],
            'variation_files' => [],
            'reference_files' => [],
        ])->willReturn(201);

        $this->create('unicorn', [
            'localized' => false,
            'description' => 'The wonderful unicorn',
            'end_of_use' => null,
            'tags' => [],
            'categories' => ['asset_main_catalog'],
            'variation_files' => [],
            'reference_files' => [],
        ])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_an_asset()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['unicorn', ['code' => 'unicorn', 'localized' => false]]);
    }

    function it_upserts_an_asset($resourceClient)
    {
        $resourceClient
            ->upsertResource(AssetApi::ASSET_URI, ['akeneo_logo'], [
                'localized' => false,
                'description' => 'Akeneo logo updated',
                'categories' => ['asset_main_catalog'],
            ])
            ->willReturn(204);

        $this->upsert('akeneo_logo', [
            'localized' => false,
            'description' => 'Akeneo logo updated',
            'categories' => ['asset_main_catalog'],
        ])->shouldReturn(204);
    }

    function it_upserts_a_list_of_assets($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient->upsertStreamResourceList(AssetApi::ASSETS_URI, [], [
            [
                'code' => 'akeneo_logo',
                'description' => 'Akeneo logo updated',
            ],
            [
                'code' => 'unicorn',
                'description' => 'Created asset',
            ]
        ])->willReturn($response);

        $this->upsertList([
            [
                'code' => 'akeneo_logo',
                'description' => 'Akeneo logo updated',
            ],
            [
                'code' => 'unicorn',
                'description' => 'Created asset',
            ]
        ])->shouldReturn($response);
    }
}
