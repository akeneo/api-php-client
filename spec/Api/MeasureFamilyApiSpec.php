<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\MeasureFamilyApi;
use Akeneo\Pim\ApiClient\Api\MeasureFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class MeasureFamilyApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(MeasureFamilyApi::class);
        $this->shouldImplement(MeasureFamilyApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_measure_family($resourceClient)
    {
        $measureFamilyCode = 'foo';
        $measureFamily = [
            "code"     => "Foo",
            "standard" => "FOO_METER",
            "units"    => [
                [
                    "code"    => "FOO_MILLIMETER",
                    "convert" => [
                        "mul" => "0.000001",
                    ],
                    "symbol"  => "mm²",
                ],
            ],
        ];

        $resourceClient
            ->getResource(MeasureFamilyApi::MEASURE_FAMILY_URI, [$measureFamilyCode])
            ->willReturn($measureFamily);

        $this->get($measureFamilyCode)->shouldReturn($measureFamily);
    }

    function it_returns_a_list_of_measure_families_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(MeasureFamilyApi::MEASURE_FAMILIES_URI, [], 10, false, [])
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
            ->getResources(MeasureFamilyApi::MEASURE_FAMILIES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_measure_families(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(MeasureFamilyApi::MEASURE_FAMILIES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_measure_families_with_additional_query_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(MeasureFamilyApi::MEASURE_FAMILIES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }
}
