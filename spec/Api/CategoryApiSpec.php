<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\CategoryApi;
use Akeneo\Pim\ApiClient\Api\CategoryApiInterface;
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

class CategoryApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient,
                 PageFactoryInterface $pageFactory,
                 ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryApi::class);
        $this->shouldImplement(CategoryApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(CreatableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_a_category($resourceClient)
    {
        $categoryCode = 'foo';
        $category = [
            'code' => 'foo',
            'parent' => null,
            'labels' => [
                'en_US' => 'Foo',
            ],
        ];

        $resourceClient
            ->getResource(CategoryApi::CATEGORY_URI, [$categoryCode])
            ->willReturn($category);

        $this->get($categoryCode)->shouldReturn($category);
    }

    function it_returns_a_list_of_categories_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_categories_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_categories(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_categories_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_category($resourceClient)
    {
        $resourceClient
            ->createResource(
                CategoryApi::CATEGORIES_URI,
                [],
                ['code' => 'master', 'parent' => 'foo']
            )
            ->willReturn(201);

        $this->create('master', ['parent' => 'foo'])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_a_category($resourceClient)
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['master', ['code' => 'master', 'parent' => 'foo']]);
    }

    function it_upserts_a_category($resourceClient)
    {
        $resourceClient
            ->upsertResource(CategoryApi::CATEGORY_URI, ['master'], ['parent' => 'foo'])
            ->willReturn(204);

        $this->upsert('master', ['parent' => 'foo'])->shouldReturn(204);
    }

    function it_upserts_a_list_of_categories($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertStreamResourceList(
                CategoryApi::CATEGORIES_URI,
                [],
                [
                    ['code' => 'category_1'],
                    ['code' => 'category_2'],
                    ['code' => 'category_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'category_1'],
                ['code' => 'category_2'],
                ['code' => 'category_3'],
            ])->shouldReturn($response);
    }
}
