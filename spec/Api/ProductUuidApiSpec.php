<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Api\ProductUuidApiInterface;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ProductUuidApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(ProductUuidApi::class);
        $this->shouldImplement(ProductUuidApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_a_product(ResourceClientInterface $resourceClient)
    {
        $uuid = '12951d98-210e-4bRC-ab18-7fdgf1bd14f3';
        $product = [
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
            'identifier' => 'foo',
            'family' => 'tshirts',
            'enabled' => true,
            'categories' => [
                'bar',
            ],
        ];

        $resourceClient
            ->getResource(ProductUuidApi::PRODUCT_UUID_URI, [$uuid], [])
            ->willReturn($product);

        $this->get($uuid)->shouldReturn($product);
    }

    function it_returns_a_product_with_query_parameters(ResourceClientInterface $resourceClient)
    {
        $uuid = '12951d98-210e-4bRC-ab18-7fdgf1bd14f3';
        $product = [
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
            'identifier' => 'foo',
            'family' => 'tshirts',
            'enabled' => true,
            'categories' => [
                'bar',
            ],
        ];

        $resourceClient
            ->getResource(ProductUuidApi::PRODUCT_UUID_URI, [$uuid], ['with_attribute_options' => true])
            ->willReturn($product);

        $this->get($uuid, ['with_attribute_options' => true])->shouldReturn($product);
    }

    function it_returns_a_list_of_products_with_default_parameters(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(ProductUuidApi::PRODUCTS_UUID_URI, [], 100, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }


    function it_returns_a_list_of_products_with_limit_and_count(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(ProductUuidApi::PRODUCTS_UUID_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_products(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ProductUuidApi::PRODUCTS_UUID_URI, [], 10, false, ['pagination_type' => 'search_after'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_products_with_additional_query_parameters(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(ProductUuidApi::PRODUCTS_UUID_URI, [], 100, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(100, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_product(ResourceClientInterface $resourceClient)
    {
        $resourceClient
            ->createResource(
                ProductUuidApi::PRODUCTS_UUID_URI,
                [],
                ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3', 'family' => 'bar']
            )
            ->willReturn(HttpClient::HTTP_CREATED);

        $this->create('12951d98-210e-4bRC-ab18-7fdgf1bd14f3', ['family' => 'bar'])->shouldReturn(
            HttpClient::HTTP_CREATED
        );
    }

    function it_throws_an_exception_if_identifier_is_provided_in_data_when_creating_a_product()
    {
        $this
            ->shouldThrow(
                new InvalidArgumentException('The parameter "uuid" should not be defined in the data parameter')
            )
            ->during(
                'create',
                [
                    '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
                    ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3', 'family' => 'bar'],
                ]
            );
    }

    function it_upserts_a_product(ResourceClientInterface $resourceClient)
    {
        $resourceClient
            ->upsertResource(
                ProductUuidApi::PRODUCT_UUID_URI,
                ['12951d98-210e-4bRC-ab18-7fdgf1bd14f3'],
                ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3', 'identifier' => 'foo', 'family' => 'bar']
            )
            ->willReturn(HttpClient::HTTP_NO_CONTENT);

        $this->upsert(
            '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
            ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3', 'identifier' => 'foo', 'family' => 'bar']
        )
            ->shouldReturn(HttpClient::HTTP_NO_CONTENT);
    }

    function it_deletes_a_product(ResourceClientInterface $resourceClient)
    {
        $resourceClient
            ->deleteResource(ProductUuidApi::PRODUCT_UUID_URI, ['12951d98-210e-4bRC-ab18-7fdgf1bd14f3'])
            ->willReturn(HttpClient::HTTP_NO_CONTENT);

        $this->delete('12951d98-210e-4bRC-ab18-7fdgf1bd14f3')->shouldReturn(HttpClient::HTTP_NO_CONTENT);
    }

    function it_upserts_a_list_of_products(
        ResourceClientInterface $resourceClient,
        UpsertResourceListResponse $response
    ) {
        $resourceClient
            ->upsertStreamResourceList(
                ProductUuidApi::PRODUCTS_UUID_URI,
                [],
                [
                    ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'],
                    ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f4'],
                    ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f5'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'],
                ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f4'],
                ['uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f5'],
            ])->shouldReturn($response);
    }
}
