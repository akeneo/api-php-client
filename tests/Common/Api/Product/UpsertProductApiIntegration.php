<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class UpsertProductApiIntegration extends AbstractProductApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getProductApi();

        $response = $api->upsert('docks_black', [
            'enabled' => false,
            'values'  => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'Black Docks',
                    ],
                ],
            ]
        ]);

        $this->assertSame(204, $response);

        $product = $this->sanitizeProductData($api->get('docks_black'));

        $this->assertSameContent($this->sanitizeProductData([
            'identifier'    => 'docks_black',
            'family'        => 'boots',
            'groups'        => [],
            'variant_group' => 'caterpillar_boots',
            'categories'    => [
                'winter_boots',
                'winter_collection',
            ],
            'enabled'       => false,
            'values'        => [
                'color'              => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => 'black',
                    ],
                ],
                'manufacturer'       => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => 'Caterpillar',
                    ],
                ],
                'name'               => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'Black Docks',
                    ],
                ],
                'size'               => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => '42',
                    ],
                ],
                'weather_conditions' => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => [
                            'cold',
                            'snowy',
                            'wet',
                        ],
                    ],
                ],
                'price'              => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => [
                            [
                                'amount'   => '149.49',
                                'currency' => 'EUR',
                            ],
                            [
                                'amount'   => '149.49',
                                'currency' => 'USD',
                            ],
                        ],
                    ],
                ],
            ],
            'created'       => '2017-06-26T07:33:09+00:00',
            'updated'       => '2017-06-26T14:48:15+00:00',
            'associations'  => []
        ]), $product);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getProductApi();
        $response = $api->upsert('pumps', [
            'enabled'    => false,
            'family'     => 'sandals',
            'categories' => ['summer_collection'],
            'values'     => [
                'name' => [
                    [
                        'data'   => 'The pumps',
                        'locale' => 'en_US',
                        'scope'  => null,
                    ],
                    [
                        'data'   => 'Les pumps',
                        'locale' => 'fr_FR',
                        'scope'  => null,
                    ]
                ],
            ]
        ]);

        $this->assertSame(201, $response);

        $product = $this->sanitizeProductData($api->get('pumps'));

        $expectedProduct = $this->sanitizeProductData([
            'identifier'    => 'pumps',
            'family'        => 'sandals',
            'groups'        => [
            ],
            'variant_group' => null,
            'categories'    => [
                'summer_collection',
            ],
            'enabled'       => false,
            'values'        => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'The pumps',
                    ],
                    [
                        'locale' => 'fr_FR',
                        'scope'  => null,
                        'data'   => 'Les pumps',
                    ],
                ],
            ],
            'created'       => '2017-06-26T14:27:19+00:00',
            'updated'       => '2017-06-26T14:27:19+00:00',
            'associations'  => [
            ],
        ]);

        $this->assertSameContent($expectedProduct, $product);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getProductApi();
        $api->upsert('docks_black', [
            'enabled' => false,
            'values'  => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => ['Black Docks'],
                    ],
                ],
            ]
        ]);
    }
}
