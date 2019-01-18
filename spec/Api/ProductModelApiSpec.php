<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\CreatableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelApi;
use Akeneo\Pim\ApiClient\Api\ProductModelApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class ProductModelApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(ProductModelApi::class);
        $this->shouldImplement(ProductModelApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(CreatableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_a_product_model($resourceClient)
    {
        $productModel = [
            'code' => 'a_product_model',
            'parent' => null
        ];

        $resourceClient
            ->getResource(ProductModelApi::PRODUCT_MODEL_URI, ['a_product_model'])
            ->willReturn($productModel);

        $this->get('a_product_model')->shouldReturn($productModel);
    }

    function it_returns_a_list_of_product_models_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(ProductModelApi::PRODUCT_MODELS_URI, [], 10, false, [])
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_product_models_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(ProductModelApi::PRODUCT_MODELS_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_products(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ProductModelApi::PRODUCT_MODELS_URI, [], 10, false, ['pagination_type' => 'search_after'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_creates_a_product_model($resourceClient)
    {
        $code = 'a_product_model';
        $productModel = ['parent' => null];

        $resourceClient
            ->createResource(
                ProductModelApi::PRODUCT_MODELS_URI,
                [],
                ['code' => $code, 'parent' => null]
            )
            ->shouldBeCalled()
            ->willReturn(201);

        $this->create($code, $productModel)->shouldReturn(201);
    }

    function it_upserts_a_product_model($resourceClient)
    {
        $code = 'a_product_model';
        $data = ['categories' => ['2014_collection', 'winter_boots']];

        $resourceClient
            ->upsertResource(
                ProductModelApi::PRODUCT_MODEL_URI,
                [$code],
                ['code' => 'a_product_model', 'categories' => ['2014_collection', 'winter_boots']]
            )
            ->shouldBeCalled()
            ->willReturn(204);

        $this->upsert($code, $data)->shouldReturn(204);
    }

    function it_upserts_a_list_of_product_models($resourceClient, UpsertResourceListResponse $response)
    {
        $data = [
            [
                'code' => 'rain_boots_red',
                'family_variant' => 'rain_boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots'],
                'values' => []
            ],
            [
                'code' => 'saddle_boots_red',
                'family_variant' => 'saddle_boots_color_size',
                'parent' => 'saddle_boots',
                'categories' => ['2014_collection', 'winter_boots'],
                'values' => []
            ]
        ];

        $resourceClient->upsertStreamResourceList(ProductModelApi::PRODUCT_MODELS_URI, [], $data)->willReturn($response);

        $this->upsertList($data)->shouldReturn($response);
    }

    function it_throws_an_exception_if_the_code_is_sent_in_data_during_create()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('create', ['a_product_model', ['code' => 'product_model']]);
    }

    function it_throws_an_exception_if_the_code_is_sent_in_data_during_upsert()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('upsert', ['a_product_model', ['code' => 'product_model']]);
    }
}
