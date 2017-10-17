<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class CreateProductApiIntegration extends AbstractProductApiTestCase
{
    /**
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
