<?php

namespace Akeneo\Pim\tests\v1_8\Api\Channel;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertChannelApiIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getChannelApi();
        $response = $api->upsert(
            'ecommerce',
            [
                'currencies'    => [
                    'EUR',
                ],
                'locales'       => [
                    'fr_FR',
                ],
                'category_tree' => '2014_collection',
                'labels'        => [
                    'en_US' => 'Ecommerce',
                    'de_DE' => '',
                    'fr_FR' => 'Ecommerce',
                ],
            ]
        );

        $this->assertSame(204, $response);

        $channel = $api->get('ecommerce');
        $this->assertSameContent(
            [
                'code'             => 'ecommerce',
                'currencies'       => [
                    'EUR',
                ],
                'locales'          => [
                    'fr_FR',
                ],
                'category_tree'    => '2014_collection',
                'conversion_units' => [],
                'labels'           => [
                    'en_US' => 'Ecommerce',
                    'fr_FR' => 'Ecommerce',
                ],
            ],
            $channel
        );
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getChannelApi();
        $response = $api->upsert(
            'paper',
            [
                'currencies'    => [
                    'USD',
                    'EUR',
                ],
                'locales'       => [
                    'en_US',
                    'fr_FR',
                ],
                'category_tree' => '2014_collection',
                'labels'        => [
                    'en_US' => 'Paper',
                    'fr_FR' => 'Papier',
                ],
            ]
        );

        $this->assertSame(201, $response);

        $channel = $api->get('paper');
        $this->assertSameContent(
            [
                'code'             => 'paper',
                'currencies'       => [
                    'USD',
                    'EUR',
                ],
                'locales'          => [
                    'en_US',
                    'fr_FR',
                ],
                'category_tree'    => '2014_collection',
                'conversion_units' => [],
                'labels'           => [
                    'en_US' => 'Paper',
                    'fr_FR' => 'Papier',
                ],
            ],
            $channel
        );
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getChannelApi();
        $api->upsert('paper', ['category_tree' => ['2014_collection']]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getChannelApi();
        $api->upsert('invalid code !');
    }
}
