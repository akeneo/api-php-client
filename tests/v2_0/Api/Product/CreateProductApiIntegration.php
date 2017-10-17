<?php

namespace Akeneo\Pim\tests\v2_0\Api\Product;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\Product\AbstractProductApiTestCase;

class CreateProductApiIntegration extends AbstractProductApiTestCase
{
    public function testCreateAnExistingProduct()
    {
        $api = $this->createClient()->getProductApi();

        try {
            $api->create('black_sneakers', [
                'enabled'    => false,
                'family'     => 'sneakers',
                'categories' => ['summer_collection'],
                'values'     => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'data'   => 'Black sneakers',
                            'scope'  => null,
                        ],
                        [
                            'data'   => 'Sneakers',
                            'locale' => 'fr_FR',
                            'scope'  => null,
                        ],
                    ],
                ]
            ]);
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame([
                [
                    'property' => 'identifier',
                    'message'  => 'The same identifier is already set on another product',
                ],
            ], $exception->getResponseErrors());
        }
    }

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
        sleep(10);
        $product = $this->sanitizeProductData($api->get('pumps'));

        $this->assertSameContent($expectedProduct, $product);
    }
}
