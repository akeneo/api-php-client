<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AttributeGroupApi;
use Akeneo\Pim\Api\AttributeGroupApiInterface;
use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\InvalidArgumentException;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class AttributeGroupApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(AttributeGroupApi::class);
        $this->shouldImplement(AttributeGroupApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_an_attribute_group($resourceClient)
    {
        $attributeGroupCode = 'foo';
        $attributeGroup = [
            'code'       => 'foo',
            'attributes' => ['sku'],
            'sort_order' => 1,
            'labels'     => [
                'en_US' => 'Foo',
            ],
        ];

        $resourceClient
            ->getResource(AttributeGroupApi::ATTRIBUTE_GROUP_URI, [$attributeGroupCode])
            ->willReturn($attributeGroup);

        $this->get($attributeGroupCode)->shouldReturn($attributeGroup);
    }

    function it_returns_a_list_of_attribute_groups_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(AttributeGroupApi::ATTRIBUTE_GROUPS_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_attribute_groups_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(AttributeGroupApi::ATTRIBUTE_GROUPS_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_attribute_groups(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(AttributeGroupApi::ATTRIBUTE_GROUPS_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_attribute_groups_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(AttributeGroupApi::ATTRIBUTE_GROUPS_URI, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_an_attribute_group($resourceClient)
    {
        $resourceClient
            ->createResource(
                AttributeGroupApi::ATTRIBUTE_GROUPS_URI,
                [],
                ['code' => 'foo']
            )
            ->willReturn(201);

        $this->create('foo', [])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_an_attribute_group($resourceClient)
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['foo', ['code' => 'foo']]);
    }

    function it_upserts_an_attribute_group($resourceClient)
    {
        $resourceClient
            ->upsertResource(AttributeGroupApi::ATTRIBUTE_GROUP_URI, ['master'], ['parent' => 'foo'])
            ->willReturn(204);

        $this->upsert('master', ['parent' => 'foo'])->shouldReturn(204);
    }

    function it_upserts_a_list_of_attribute_groups($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertResourceList(
                AttributeGroupApi::ATTRIBUTE_GROUPS_URI,
                [],
                [
                    ['code' => 'attribute_group_1'],
                    ['code' => 'attribute_group_2'],
                    ['code' => 'attribute_group_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'attribute_group_1'],
                ['code' => 'attribute_group_2'],
                ['code' => 'attribute_group_3'],
            ])->shouldReturn($response);
    }
}
