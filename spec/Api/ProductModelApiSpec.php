<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\ProductModelApi;
use Akeneo\Pim\Api\ProductModelApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\InvalidArgumentException;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
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
