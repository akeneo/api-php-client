<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\FamilyVariantApi;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FamilyVariantApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
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
            ->createResource(FamilyVariantApi::FAMILY_VARIANTS_URI, ['familyCode'], $completeData)
            ->shouldBeCalled()
            ->willReturn(201);

        $this->create('familyCode', $code, $data)->shouldReturn(201);
    }

    function it_returns_a_list_of_family_variants_with_default_parameters(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(FamilyVariantApi::FAMILY_VARIANTS_URI, ['books'], 10, false, [])
            ->willReturn([]);
        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage('books')->shouldReturn($page);
    }

    function it_returns_a_list_of_family_variants_with_limit_and_count(
        $resourceClient,
        $pageFactory,
        PageInterface $page
    ) {
        $resourceClient
            ->getResources(FamilyVariantApi::FAMILY_VARIANTS_URI, ['books'], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage('books', 10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_family_variants(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(
                FamilyVariantApi::FAMILY_VARIANTS_URI,
                ['books'],
                10,
                false,
                []
            )
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all('books', 10, [])->shouldReturn($cursor);
    }

    function it_udpates_a_family_variant($resourceClient)
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
            ->upsertResource(FamilyVariantApi::FAMILY_VARIANT_URI, ['familyCode', 'boots_color_size'], $completeData)
            ->shouldBeCalled()
            ->willReturn(204);

        $this->upsert('familyCode', $code, $data)->shouldReturn(204);
    }

    function it_upserts_a_list_of_family_variants($resourceClient, UpsertResourceListResponse $response)
    {
        $data = [
            [
                'code' => 'rain_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'Rain boots by color and size',
                    'fr_FR' => 'Bottes de pluie par couleur et taille'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => [
                            'name',
                            'description',
                            'side_view',
                            'color'
                        ]
                    ]
                ]
            ],
            [
                'code' => 'sun_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'Sun boots by color and size',
                    'fr_FR' => 'Bottes de soleil par couleur et taille'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => [
                            'name',
                            'description',
                            'side_view',
                            'color'
                        ]
                    ]
                ]
            ]
        ];

        $resourceClient
            ->upsertStreamResourceList(FamilyVariantApi::FAMILY_VARIANTS_URI, ['boots'], $data)
            ->willReturn($response);

        $this->upsertList('boots', $data)->shouldReturn($response);
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

    function it_throws_an_exception_if_family_is_provided_in_data_during_upsert($resourceClient)
    {
        $resourceClient->upsertResource(Argument::cetera())->shouldNotBeCalled();

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('upsert', ['family', 'family_variant_code', ['family' => 'family']]);
    }

    function it_throws_an_exception_if_family_variant_code_is_provided_in_data_during_upsert($resourceClient)
    {
        $resourceClient->upsertResource(Argument::cetera())->shouldNotBeCalled();

        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('upsert', ['family', 'family_variant_code', ['code' => 'family_variant']]);
    }
}
