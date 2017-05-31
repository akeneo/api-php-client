<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\FamilyApi;
use Akeneo\Pim\Api\FamilyApiInterface;
use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class FamilyApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(FamilyApi::class);
        $this->shouldImplement(FamilyApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_family($resourceClient)
    {
        $familyCode = 'mugs';
        $family = [
            'code' => 'mugs',
            'attributes' => [
                'foo',
                'bar',
            ],
            'attribute_as_label' => 'name',
        ];

        $resourceClient
            ->getResource(FamilyApi::FAMILY_PATH, [$familyCode])
            ->willReturn($family);

        $this->get($familyCode)->shouldReturn($family);
    }

    function it_returns_a_list_of_families_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(FamilyApi::FAMILIES_PATH, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_families_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(FamilyApi::FAMILIES_PATH, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_families(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(FamilyApi::FAMILIES_PATH, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_families_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(FamilyApi::FAMILIES_PATH, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_family($resourceClient)
    {
        $resourceClient
            ->createResource(
                FamilyApi::FAMILIES_PATH,
                [],
                ['code' => 'foo', 'attribute_as_label' => 'name']
            )
            ->willReturn(201);

        $this->create('foo', ['attribute_as_label' => 'name'])->shouldReturn(201);
    }

    function it_throws_an_exception_if_code_is_provided_in_data_when_creating_a_family()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('The parameter "code" should not be defined in the data parameter'))
            ->during('create', ['foo', ['code' => 'foo', 'attribute_as_label' => 'name']]);
    }

    function it_upserts_a_family($resourceClient)
    {
        $resourceClient
            ->upsertResource(FamilyApi::FAMILY_PATH, ['foo'], ['code' => 'foo' , 'attribute_as_label' => 'sku'])
            ->willReturn(204);

        $this->upsert('foo', ['code' => 'foo' , 'attribute_as_label' => 'sku'])
            ->shouldReturn(204);
    }

    function it_upserts_a_list_of_families($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertResourceList(
                FamilyApi::FAMILIES_PATH,
                [],
                [
                    ['code' => 'family_1'],
                    ['code' => 'family_2'],
                    ['code' => 'family_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'family_1'],
                ['code' => 'family_2'],
                ['code' => 'family_3'],
            ])->shouldReturn($response);
    }
}
