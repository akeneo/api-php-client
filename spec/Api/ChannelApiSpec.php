<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\ChannelApi;
use Akeneo\Pim\ApiClient\Api\ChannelApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\CreatableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class ChannelApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(ChannelApi::class);
        $this->shouldImplement(ChannelApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(CreatableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_a_channel($resourceClient)
    {
        $channelCode = 'foo';
        $channel = [
            'code' => 'foo',
            'category_tree' => 'master',
            'labels' => [
                'en_US' => 'Foo',
            ],
        ];

        $resourceClient
            ->getResource(ChannelApi::CHANNEL_URI, [$channelCode])
            ->willReturn($channel);

        $this->get($channelCode)->shouldReturn($channel);
    }

    function it_returns_a_list_of_channels_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ChannelApi::CHANNELS_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_channels_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ChannelApi::CHANNELS_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_channels(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ChannelApi::CHANNELS_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_channels_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ChannelApi::CHANNELS_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_channel($resourceClient)
    {
        $resourceClient
            ->createResource(
                ChannelApi::CHANNELS_URI,
                [],
                [
                    'code' => 'paper_catalog',
                    'labels' => ['en_US' => 'Paper catalog']
                ]
            )
            ->willReturn(201);

        $this->create('paper_catalog', ['labels' => ['en_US' => 'Paper catalog']])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_a_channel($resourceClient)
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['paper_catalog', ['code' => 'paper_catalog', 'labels' => ['en_US' => 'Paper catalog']]]);
    }

    function it_upserts_a_channel($resourceClient)
    {
        $resourceClient
            ->upsertResource(ChannelApi::CHANNEL_URI, ['master'], ['parent' => 'foo'])
            ->willReturn(204);

        $this->upsert('master', ['parent' => 'foo'])->shouldReturn(204);
    }

    function it_upserts_a_list_of_channel($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertStreamResourceList(
                ChannelApi::CHANNELS_URI,
                [],
                [
                    ['code' => 'channels_1'],
                    ['code' => 'channels_2'],
                    ['code' => 'channels_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'channels_1'],
                ['code' => 'channels_2'],
                ['code' => 'channels_3'],
            ])->shouldReturn($response);
    }
}
