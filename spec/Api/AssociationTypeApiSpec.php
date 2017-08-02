<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AssociationTypeApi;
use Akeneo\Pim\Api\AssociationTypeApiInterface;
use Akeneo\Pim\Api\GettableResourceInterface;
use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
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
            ->getResources(AssociationTypeApi::ASSOCIATION_TYPES_URI, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }
}
