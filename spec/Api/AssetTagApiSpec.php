<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssetTagApi;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class AssetTagApiSpec extends ObjectBehavior
{
    public function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\ApiClient\Api\AssetTagApi');
    }

    public function it_gets_an_asset_tag($resourceClient)
    {
        $assetTag = ['code' => 'logo'];

        $resourceClient->getResource(AssetTagApi::ASSET_TAG_URI, ['logo'])->willReturn($assetTag);

        $this->get('logo')->shouldReturn($assetTag);
    }

    public function it_upserts_an_asset_tag($resourceClient)
    {
        $resourceClient
            ->upsertResource(AssetTagApi::ASSET_TAG_URI, ['logo'], [])
            ->willReturn(201);

        $this->upsert('logo')->shouldReturn(201);
    }

    function it_returns_a_list_of_asset_tags_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetTagApi::ASSET_TAGS_URI, [], 100, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_asset_tags_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetTagApi::ASSET_TAGS_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_asset_tags(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(
                AssetTagApi::ASSET_TAGS_URI,
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

    function it_returns_a_list_of_asset_tags_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssetTagApi::ASSET_TAGS_URI, [], 10, true, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true, ['foo' => 'bar'])->shouldReturn($page);
    }
}
