<?php

namespace Akeneo\Pim\tests\v2_0\Api\ProductModel;

use Akeneo\Pim\tests\Common\Api\Product\AbstractProductApiTestCase;

class GetProductModelApiIntegration extends AbstractProductApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getProductModelApi();

        $productModel = $api->get('rain_boots_red');
        $productModel = $this->sanitizeProductData($productModel);

        $this->assertSameContent($this->sanitizeProductData([
            'code' => 'rain_boots_red',
            'family_variant' => 'boots_color_size',
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
            ],
            'created' => '2017-10-17T14:12:35+00:00',
            'updated' => '2017-10-17T14:12:35+00:00'
        ]), $productModel);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     * @expectedExceptionMessage Product model "trololo" does not exist.
     */
    public function testEntityNotFound()
    {
        $this->createClient()->getProductModelApi()->get('trololo');
    }
}
