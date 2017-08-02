<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AttributeGroupApi;
use Akeneo\Pim\Api\AttributeGroupApiInterface;
use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
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
}
