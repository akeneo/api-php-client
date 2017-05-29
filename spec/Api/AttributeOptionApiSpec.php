<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AttributeOptionApi;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class AttributeOptionApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(AttributeOptionApi::class);
        $this->shouldImplement(AttributeOptionApiInterface::class);
    }

    function it_returns_an_attribute_option($resourceClient)
    {
        $attributeCode = 'foo';
        $attributeOptionCode = 'bar';

        $attributeOption = [
            'code' => 'foo',
            'attribute' => 'bar',
            'sort_order' => 2,
            'labels' => [
                'en_US' => 'Foo',
            ],
        ];

        $resourceClient
            ->getResource(AttributeOptionApi::ATTRIBUTE_OPTION_PATH, [$attributeCode, $attributeOptionCode])
            ->willReturn($attributeOption);

        $this->get($attributeCode, $attributeOptionCode)->shouldReturn($attributeOption);
    }

    function it_returns_a_list_of_attribute_options_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $attributeCode = 'foo_1';

        $resourceClient
            ->getResources(sprintf(AttributeOptionApi::ATTRIBUTE_OPTIONS_PATH, $attributeCode), [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage($attributeCode)->shouldReturn($page);
    }

    function it_returns_a_list_of_attribute_options_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $attributeCode = 'foo_1';

        $resourceClient
            ->getResources(sprintf(AttributeOptionApi::ATTRIBUTE_OPTIONS_PATH, $attributeCode), [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage($attributeCode, 10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_attribute_options(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $attributeCode = 'foo_1';

        $resourceClient
            ->getResources(sprintf(AttributeOptionApi::ATTRIBUTE_OPTIONS_PATH, $attributeCode), [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all($attributeCode, 10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_attribute_options_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $attributeCode = 'foo_1';

        $resourceClient
            ->getResources(sprintf(AttributeOptionApi::ATTRIBUTE_OPTIONS_PATH, $attributeCode), [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage($attributeCode, null, null, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_an_attribute_option($resourceClient)
    {
        $resourceClient
            ->createResource(
                AttributeOptionApi::ATTRIBUTE_OPTIONS_PATH,
                ['bar'],
                ['code' => 'foo', 'attribute' => 'bar', 'sort_order' => 2]
            )
            ->willReturn(201);

        $this->create('bar', 'foo', ['sort_order' => 2])->shouldReturn(201);
    }

    function it_throws_an_exception_if_attribute_option_code_is_provided_in_data_when_creating_an_attribute_option()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['foo', 'bar', ['code' => 'foo', 'sort_order' => 2]]);
    }

    function it_throws_an_exception_if_attribute_code_is_provided_in_data_when_creating_an_attribute_option()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('The parameter "attribute" should not be defined in the data parameter'))
            ->during('create', ['foo', 'bar', ['attribute' => 'bar', 'sort_order' => 2]]);
    }

    function it_updates_partially_an_attribute_option($resourceClient)
    {
        $resourceClient
            ->partialUpdateResource(
                AttributeOptionApi::ATTRIBUTE_OPTION_PATH,
                ['foo', 'bar'],
                ['code' => 'bar', 'attribute' => 'foo', 'sort_order' => 42]
            )
            ->willReturn(204);

        $this
            ->upsert('foo', 'bar', ['code' => 'bar', 'attribute' => 'foo', 'sort_order' => 42])
            ->shouldReturn(204);
    }
}
