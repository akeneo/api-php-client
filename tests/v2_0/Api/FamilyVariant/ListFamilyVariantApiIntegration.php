<?php

namespace Akeneo\Pim\tests\v2_0\Api\FamilyVariant;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class ListFamilyVariantApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getFamilyVariantApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];
        $expectedFamilyVariants = $this->getExpectedFamilyVariants();

        $firstPage = $api->listPerPage('boots', 2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/families/boots/variants?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $familyVariants = $firstPage->getItems();
        $this->assertCount(2 ,$familyVariants);
        $this->assertSameContent($expectedFamilyVariants[0], $familyVariants[0]);
        $this->assertSameContent($expectedFamilyVariants[1], $familyVariants[1]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/families/boots/variants?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/families/boots/variants?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $familyVariants = $secondPage->getItems();
        $this->assertCount(2 ,$familyVariants);
        $this->assertSameContent($expectedFamilyVariants[2], $familyVariants[0]);
        $this->assertSameContent($expectedFamilyVariants[3], $familyVariants[1]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/families/boots/variants?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());

        $familyVariants = $lastPage->getItems();
        $this->assertCount(0 ,$familyVariants);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testAll()
    {
        $api = $this->createClient()->getFamilyVariantApi();
        $familyVariants = $api->all('boots');

        $this->assertInstanceOf(ResourceCursorInterface::class, $familyVariants);

        $familyVariants = iterator_to_array($familyVariants);

        $this->assertCount(4, $familyVariants);
        $this->assertSameContent($this->getExpectedFamilyVariants(), $familyVariants);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getFamilyVariantApi();
        $familyVariants = $api->all('boots', 10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $familyVariants);

        $familyVariants = iterator_to_array($familyVariants);

        $this->assertCount(4, $familyVariants);
        $this->assertSameContent($this->getExpectedFamilyVariants(), $familyVariants);
    }

    /**
     * @return array
     */
    protected function getExpectedFamilyVariants()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/boots/variants/city_boots_color_size',
                    ],
                ],
                'code' => 'city_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'City boots by color and size',
                    'fr_FR' => 'Bottes de ville par couleur et taille'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => ['name', 'description', 'side_view', 'color']
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size']
                    ]
                ]
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/boots/variants/man_boots_color_size',
                    ]
                ],
                'code' => 'man_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'Man boots by color and size',
                    'fr_FR' => 'Bottes homme par couleur et taille'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => ['name', 'description', 'side_view', 'color']
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size'],
                    ]
                ]
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/boots/variants/rain_boots_color_size'
                    ]
                ],
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
                        'attributes' => ['name', 'description', 'side_view', 'color']
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size']
                    ]
                ]
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/boots/variants/woman_boots_color_size'
                    ]
                ],
                'code' => 'woman_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'Woman boots by color and size',
                    'fr_FR' => 'Bottes femme par couleur et taille'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => ['name', 'description', 'side_view', 'color']
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size']
                    ]
                ]
            ]
        ];
    }
}
