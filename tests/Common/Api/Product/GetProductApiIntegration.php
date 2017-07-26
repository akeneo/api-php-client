<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class GetProductApiIntegration extends AbstractProductApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getProductApi();

        $product = $api->get('black_sneakers');
        $product = $this->sanitizeProductData($product);

        $this->assertSameContent($this->sanitizeProductData([
            'identifier'    => 'black_sneakers',
            'family'        => 'sneakers',
            'groups'        => [
            ],
            'variant_group' => null,
            'categories'    => [
                'summer_collection',
                'winter_collection',
            ],
            'enabled'       => true,
            'values'        => [
                'color'              => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => 'black',
                    ],
                ],
                'description'        => [
                    [
                        'locale' => 'en_US',
                        'scope'  => 'ecommerce',
                        'data'   => 'The famous sneakers',
                    ],
                    [
                        'locale' => 'fr_FR',
                        'scope'  => 'ecommerce',
                        'data'   => 'Les fameuses sneakers',
                    ],
                ],
                'manufacturer'       => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => 'Converse',
                    ],
                ],
                'name'               => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'Black sneakers',
                    ],
                    [
                        'locale' => 'fr_FR',
                        'scope'  => null,
                        'data'   => 'Sneakers',
                    ],
                ],
                'side_view'          => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => '9/8/e/d/98eda36deba5e392f5c9e0dd2d9ef194b045b2af_Ziggy_certification.jpg',
                        '_links' => [
                            'download' => [
                                'href' => 'http://localhost/api/rest/v1/media-files/9/8/e/d/98eda36deba5e392f5c9e0dd2d9ef194b045b2af_Ziggy_certification.jpg/download',
                            ],
                        ],
                    ],
                ],
                'size'               => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => '41',
                    ],
                ],
                'weather_conditions' => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => [
                            'dry',
                            'wet',
                        ],
                    ],
                ],
                'length'             => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => [
                            'amount' => 14,
                            'unit'   => 'CENTIMETER',
                        ],
                    ],
                ],
                'price'              => [
                    [
                        'locale' => null,
                        'scope'  => null,
                        'data'   => [
                            [
                                'amount'   => '40.00',
                                'currency' => 'EUR',
                            ],
                            [
                                'amount'   => '42.00',
                                'currency' => 'USD',
                            ],
                        ],
                    ],
                ],
            ],
            'created'       => '2017-06-22T14:08:29+00:00',
            'updated'       => '2017-06-22T14:08:29+00:00',
            'associations'  => [],
        ]), $product);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getProductApi();

        $api->get('pumps');
    }
}
