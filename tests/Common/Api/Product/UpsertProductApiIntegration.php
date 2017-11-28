<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Product;

class UpsertProductApiIntegration extends AbstractProductApiTestCase
{
    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
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
