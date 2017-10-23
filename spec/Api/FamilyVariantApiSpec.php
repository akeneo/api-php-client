<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\FamilyVariantApi;
use Akeneo\Pim\Client\ResourceClientInterface;
use PhpSpec\ObjectBehavior;

class FamilyVariantApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient)
    {
        $this->beConstructedWith($resourceClient);
    }

    function it_returns_a_family_variant($resourceClient)
    {
        $familyCode = 'shoes';
        $familyVariantCode = 'blue_shoes';
        $familyVariant = [
            'code' => 'blue_shoes',
            'family' => 'shoes',
            'labels' => [
                'en_US' => 'Shoes',
            ],
            'variant_attribute_sets' => [
                [
                    'level' => 1,
                    'axes' => ['color'],
                    'attributes' => ['description']
                ]
            ]
        ];

        $resourceClient
            ->getResource(FamilyVariantApi::FAMILY_VARIANT_URI, [$familyCode, $familyVariantCode])
            ->willReturn($familyVariant);

        $this->get($familyCode, $familyVariantCode)->shouldReturn($familyVariant);
    }
}
