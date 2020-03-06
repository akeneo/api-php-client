<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\MeasurementFamilyApi;
use Akeneo\Pim\ApiClient\Api\MeasurementFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;

class MeasurementFamilyApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(MeasurementFamilyApi::class);
        $this->shouldImplement(MeasurementFamilyApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(UpsertableResourceListInterface::class);
    }

    function it_returns_a_list_of_measure_families_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI, [], 100, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_measure_families_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI, [], 100, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(100, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_measure_families(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI, [], 100, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(100, $page)->willReturn($cursor);

        $this->all(100, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_measure_families_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI, [], 100, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(100, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_upserts_a_list_of_measurement_families($resourceClient, UpsertResourceListResponse $response)
    {
        $resourceClient
            ->upsertStreamResourceList(
              MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI,
                [],
                [
                    ['code' => 'measurement_family_1'],
                    ['code' => 'measurement_family_2'],
                    ['code' => 'measurement_family_3'],
                ]
            )
            ->willReturn($response);

        $this
            ->upsertList([
                ['code' => 'measurement_family_1'],
                ['code' => 'measurement_family_2'],
                ['code' => 'measurement_family_3'],
            ])->shouldReturn($response);
    }
}
