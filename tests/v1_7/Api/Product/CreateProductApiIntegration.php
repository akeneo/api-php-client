<?php

namespace Akeneo\Pim\tests\v1_7\Api\Product;

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
                    'message'  => 'The value black_sneakers is already set on another product for the unique attribute sku',
                ],
            ], $exception->getResponseErrors());
        }
    }
}
