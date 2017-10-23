<?php

namespace Akeneo\Pim\tests\v2_0\Api\ProductModel;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\Product\AbstractProductApiTestCase;

class ListProductModelApiIntegration extends AbstractProductApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getProductModelApi();
        $expectedProductModels = $this->getExpectedProductModels();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame(
            $baseUri . '/api/rest/v1/product-models?page=2&with_count=false&pagination_type=page&limit=2',
            $firstPage->getNextLink()
        );

        $firstPageProducts = $this->sanitizeProductData($firstPage->getItems());
        $firstPageExpectedProducts = $this->sanitizeProductData(array_slice($expectedProductModels, 0, 2));

        $this->assertSameContent($firstPageExpectedProducts, $firstPageProducts);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame(
            $baseUri . '/api/rest/v1/product-models?page=1&with_count=false&pagination_type=page&limit=2',
            $secondPage->getPreviousLink()
        );
        $this->assertSame(
            $baseUri . '/api/rest/v1/product-models?page=3&with_count=false&pagination_type=page&limit=2',
            $secondPage->getNextLink()
        );

        $secondPageProducts = $this->sanitizeProductData($secondPage->getItems());
        $secondPageExpectedProducts = $this->sanitizeProductData(array_slice($expectedProductModels, 2, 2));

        $this->assertSameContent($secondPageExpectedProducts, $secondPageProducts);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame(
            $baseUri . '/api/rest/v1/product-models?page=2&with_count=false&pagination_type=page&limit=2',
            $lastPage->getPreviousLink()
        );

        $productModels = $lastPage->getItems();
        $this->assertCount(0 , $productModels);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getProductModelApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(2, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(4, $firstPage->getCount());
        $this->assertSame(
            $baseUri . '/api/rest/v1/product-models?page=2&with_count=true&pagination_type=page&limit=2',
            $firstPage->getNextLink()
        );
    }

    public function testAll()
    {
        $api = $this->createClient()->getProductModelApi();
        $productModels = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $productModels);

        $expectedProductModels = $this->sanitizeProductData($this->getExpectedProductModels());
        $productModels = $this->sanitizeProductData(iterator_to_array($productModels));

        $this->assertSameContent($expectedProductModels, $productModels);
    }

    protected function getExpectedProductModels()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/product-models/rain_boots'
                    ]
                ],
                'code' => 'rain_boots',
                'family_variant' => 'boots_color_size',
                'parent' => null,
                'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
                'values' => [
                    'price' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => [
                                [
                                    'amount' => null,
                                    'currency' => 'EUR'
                                ],
                                [
                                    'amount' => '42.00',
                                    'currency' => 'USD'
                                ]
                            ]
                        ]
                    ]
                ],
                'created' => '2017-10-23T13:18:15+00:00',
                'updated' => '2017-10-23T13:18:15+00:00'
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/product-models/rain_boots_blue'
                    ]
                ],
                'code' => 'rain_boots_blue',
                'family_variant' => 'boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
                'values' => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope' => null,
                            'data' => 'Blue rain boots'
                        ],
                        [
                            'locale' => 'fr_FR',
                            'scope' => null,
                            'data' => 'Bottes de pluie bleues'
                        ]
                    ],
                    'color' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => 'blue'
                        ]
                    ],
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Blue rain boots made of rubber for winter.'
                        ]
                    ],
                    'price' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => [
                                [
                                    'amount' => null,
                                    'currency' => 'EUR'
                                ],
                                [
                                    'amount' => '42.00',
                                    'currency' => 'USD'
                                ]
                            ]
                        ]
                    ]
                ],
                'created' => '2017-10-23T13:18:16+00:00',
                'updated' => '2017-10-23T13:18:16+00:00'
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/product-models/rain_boots_red'
                    ]
                ],
                'code' => 'rain_boots_red',
                'family_variant' => 'boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
                'values' => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope' => null,
                            'data' => 'Red rain boots'
                        ],
                        [
                            'locale' => 'fr_FR',
                            'scope' => null,
                            'data' => 'Bottes de pluie rouges'
                        ]
                    ],
                    'color' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => 'red'
                        ]
                    ],
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Red rain boots made of rubber for winter.'
                        ]
                    ],
                    'price' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => [
                                [
                                    'amount' => null,
                                    'currency' => 'EUR'
                                ],
                                [
                                    'amount' => '42.00',
                                    'currency' => 'USD'
                                ]
                            ]
                        ]
                    ]
                ],
                'created' => '2017-10-23T13:18:16+00:00',
                'updated' => '2017-10-23T13:18:16+00:00'
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/product-models/rain_boots_charcoal'
                    ]
                ],
                'code' => 'rain_boots_charcoal',
                'family_variant' => 'boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
                'values' => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope' => null,
                            'data' => 'Charcoal rain boots'
                        ],
                        [
                            'locale' => 'fr_FR',
                            'scope' => null,
                            'data' => 'Bottes de pluie couleur charbon'
                        ]
                    ],
                    'color' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => 'charcoal'
                        ]
                    ],
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Charcoal rain boots made of rubber for winter.'
                        ]
                    ],
                    'price' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => [
                                [
                                    'amount' => null,
                                    'currency' => 'EUR'
                                ],
                                [
                                    'amount' => '42.00',
                                    'currency' => 'USD'
                                ]
                            ]
                        ]
                    ]
                ],
                'created' => '2017-10-23T13:18:16+00:00',
                'updated' => '2017-10-23T13:18:16+00:00'
            ]
        ];
    }
}
