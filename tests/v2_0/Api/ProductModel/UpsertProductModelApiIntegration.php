<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\ProductModel;

use Akeneo\Pim\ApiClient\tests\Common\Api\Product\AbstractProductApiTestCase;

class UpsertProductModelApiIntegration extends AbstractProductApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getProductModelApi();

        $response = $api->upsert('rain_boots_red', [
            'values' => [
                'description' => [
                    [
                        'locale' => 'en_US',
                        'scope' => 'ecommerce',
                        'data' => 'Red rain boots en_US.'
                    ]
                ]
            ]
        ]);

        $this->assertSame(204, $response);

        $productModel = $this->sanitizeProductData($api->get('rain_boots_red'));

        $this->assertSameContent($this->sanitizeProductData([
            'code' => 'rain_boots_red',
            'family_variant' => 'rain_boots_color_size',
            'parent' => 'rain_boots',
            'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
            'values' => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope' => null,
                        'data' => 'Red rain boots',
                    ],
                    [
                        'locale' => 'fr_FR',
                        'scope' => null,
                        'data' => 'Bottes de pluie rouges',
                    ],
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
                        'data' => 'Red rain boots en_US.'
                    ]
                ],
                'price' => [
                    [
                        'locale' => null,
                        'scope' => null,
                        'data' => [
                            [
                                'amount' => null,
                                'currency' => 'EUR',
                            ],
                            [
                                'amount' => '42.00',
                                'currency' => 'USD',
                            ],
                        ]
                    ]
                ]
            ],
            'created' => '2017-10-17T14:12:35+00:00',
            'updated' => '2017-10-17T14:12:35+00:00'
        ]), $productModel);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getProductModelApi();
        $data = [
            'family_variant' => 'rain_boots_color_size',
            'parent' => 'rain_boots',
            'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
            'values' => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope' => null,
                        'data' => 'Red rain boots',
                    ],
                    [
                        'locale' => 'fr_FR',
                        'scope' => null,
                        'data' => 'Bottes de pluie rouges',
                    ],
                ],
                'color' => [
                    [
                        'locale' => null,
                        'scope' => null,
                        'data' => 'saddle'
                    ]
                ],
                'description' => [
                    [
                        'locale' => 'en_US',
                        'scope' => 'ecommerce',
                        'data' => 'Saddle rain boots made of rubber for winter.'
                    ]
                ],
                'price' => [
                    [
                        'locale' => null,
                        'scope' => null,
                        'data' => [
                            [
                                'amount' => null,
                                'currency' => 'EUR',
                            ],
                            [
                                'amount' => '42.00',
                                'currency' => 'USD',
                            ],
                        ]
                    ]
                ]
            ]
        ];
        $response = $api->upsert('saddle', $data);

        $this->assertSame(201, $response);
        sleep(10);
        $productModel = $this->sanitizeProductData($api->get('saddle'));

        $expectedProductModel = $this->sanitizeProductData(array_merge(['code' => 'saddle'], $data));

        $this->assertSameContent($expectedProductModel, $productModel);
    }
}
