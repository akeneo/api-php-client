<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class CreateProductApiIntegration extends AbstractProductApiTestCase
{
    /**
     * @group common
     */
    public function testCreate()
    {
        $api = $this->createClient()->getProductApi();
        $response = $api->create('pumps', [
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

        $product = $this->sanitizeProductData($api->get('pumps'));

        $this->assertSameContent($expectedProduct, $product);
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidProduct()
    {
        $api = $this->createClient()->getProductApi();
        $api->create('pumps', [
            'enabled'    => false,
            'family'     => 'unknown_family',
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
    }
}
