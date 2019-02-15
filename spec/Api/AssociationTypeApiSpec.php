<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssociationTypeApi;
use Akeneo\Pim\ApiClient\Api\AssociationTypeApiInterface;
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

class AssociationTypeApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(AssociationTypeApi::class);
        $this->shouldImplement(AssociationTypeApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(CreatableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_an_association_type($resourceClient)
    {
        $associationTypeCode = 'X_SELL';
        $associationType = [
            'code' => 'X_SELL',
            'labels' => [
                'en_US' => 'Cross sell',
                'fr_FR' => 'Vente croisée',
            ],
        ];

        $resourceClient
            ->getResource(AssociationTypeApi::ASSOCIATION_TYPE_URI, [$associationTypeCode])
            ->willReturn($associationType);

        $this->get($associationTypeCode)->shouldReturn($associationType);
    }

    function it_returns_a_list_of_association_types_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssociationTypeApi::ASSOCIATION_TYPES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_association_types_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssociationTypeApi::ASSOCIATION_TYPES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_association_types(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(AssociationTypeApi::ASSOCIATION_TYPES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_association_types_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(AssociationTypeApi::ASSOCIATION_TYPES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_an_association_type($resourceClient)
    {
        $resourceClient
            ->createResource(
                AssociationTypeApi::ASSOCIATION_TYPES_URI,
                [],
                ['code' => 'NEW_SELL']
            )
            ->willReturn(201);

        $this->create('NEW_SELL', [])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_an_association_type($resourceClient)
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['NEW_SELL', ['code' => 'NEW_SELL']]);
    }

    function it_upserts_an_association_type($resourceClient)
    {
        $resourceClient
            ->upsertResource(AssociationTypeApi::ASSOCIATION_TYPE_URI, ['UPSELL'], [])
            ->willReturn(204);

        $this->upsert('UPSELL', [])->shouldReturn(204);
    }

    function it_upserts_a_list_of_association_types($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertStreamResourceList(
                AssociationTypeApi::ASSOCIATION_TYPES_URI,
                [],
                [
                    ['code' => 'association_type_1'],
                    ['code' => 'association_type_2'],
                    ['code' => 'association_type_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'association_type_1'],
                ['code' => 'association_type_2'],
                ['code' => 'association_type_3'],
            ])->shouldReturn($response);
    }
}
