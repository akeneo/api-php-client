<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\Page;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Routing\Route;
use PhpSpec\ObjectBehavior;

class CategoryApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient, PageFactoryInterface $pageFactory)
    {
        $this->beConstructedWith($resourceClient, $pageFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryApi::class);
        $this->shouldImplement(CategoryApiInterface::class);
    }

    function it_returns_a_list_of_categories_with_default_parameters($resourceClient, $pageFactory, Page $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_PATH, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->getCategories()->shouldReturn($page);
    }

    function it_returns_a_list_of_categories_with_limit_and_count($resourceClient, $pageFactory, Page $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_PATH, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->getCategories(10, true)->shouldReturn($page);
    }

    function it_returns_a_list_of_categories_with_additional_query_parameters($resourceClient, $pageFactory, Page $page)
    {
        $resourceClient
            ->getResources(CategoryApi::CATEGORIES_PATH, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->getCategories(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }
}
