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
    function let(ResourceClientInterface $resourceClient) {
        $this->beConstructedWith($resourceClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MeasurementFamilyApi::class);
        $this->shouldImplement(MeasurementFamilyApiInterface::class);
    }

    function it_returns_all_measurement_families($resourceClient) {
        $resourceClient->getResource(MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI)->willReturn([]);
        $this->all()->shouldReturn([]);
    }

    function it_upserts_a_list_of_measurement_families($resourceClient)
    {
        $resourceClient->upsertJsonResourceList(
            MeasurementFamilyApi::MEASUREMENT_FAMILIES_URI,
            [],
            [
                ['code' => 'measurement_family_1'],
                ['code' => 'measurement_family_2'],
                ['code' => 'measurement_family_3'],
            ]
        )->willReturn([]);

        $this->upsertList(
            [
                ['code' => 'measurement_family_1'],
                ['code' => 'measurement_family_2'],
                ['code' => 'measurement_family_3'],
            ]
        )->shouldReturn([]);
    }
}
