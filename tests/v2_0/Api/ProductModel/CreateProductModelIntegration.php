<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\ProductModel;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class CreateProductModelIntegration extends ApiTestCase
{
    public function testCreateAProductModel()
    {
        $api = $this->createClient()->getProductModelApi();
        $code = 'saddle';
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
        $response = $api->create($code, $data);
        $this->assertSame(201, $response);

        // We need this because of product model post save events.
        sleep(5);
        $productModel = $api->get($code);

        $data['code'] = $code;
        $this->assertSameContent($data, $productModel);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     * @expectedExceptionMessage Validation failed
     */
    public function testFailToCreateAProductModel()
    {
        $api = $this->createClient()->getProductModelApi();
        $code = 'red';
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
        $response = $api->create($code, $data);
        $this->assertSame(422, $response);
    }
}
