<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\FamilyVariantApi;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

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

    function it_returns_a_list_of_product_models_with_default_parameters(
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
}
