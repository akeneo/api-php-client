<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\FamilyVariantApi;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_throws_an_exception_if_family_is_provided_in_data_during_creation($resourceClient)
    {
        $resourceClient->createResource(Argument::cetera())->shouldNotBeCalled();

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('create', ['family', 'family_variant_code', ['family' => 'family']]);
    }

    function it_throws_an_exception_if_family_variant_code_is_provided_in_data_during_creation($resourceClient)
    {
        $resourceClient->createResource(Argument::cetera())->shouldNotBeCalled();

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('create', ['family', 'family_variant_code', ['code' => 'family_variant']]);
    }

    function it_creates_a_family_variant($resourceClient)
    {
        $code = 'boots_color_size';
        $data = [
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe',
                'en_US' => 'Boots by color and size',
                'fr_FR' => 'Bottes par couleur et taille'
            ],
            'variant_attribute_sets' => [
                [
                    'level' => 1,
                    'axes' => ['color'],
                    'attributes' => [
                        'name',
                        'description',
                        'color',
                        'sku'
                    ]
                ]
            ]
        ];
        $completeData = array_merge(['code' => $code], $data);

        $resourceClient
            ->createResource(FamilyVariantApi::CREATE_FAMILY_VARIANT_URI, ['familyCode'], $completeData)
            ->shouldBeCalled()
            ->willReturn(201);

        $this->create('familyCode', $code, $data)->shouldReturn(201);
    }
}
