<?php

namespace Akeneo\Pim\tests\v2_0\Api\Channel;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListChannelApiIntegration extends ApiTestCase
{
    public function testUpsertListSuccessful()
    {
        $api = $this->createClient()->getChannelApi();
        $response = $api->upsertList([
            [
                'code' => 'ecommerce',
                'labels' => [
                    'en_US' => 'Ecommerce',
                    'fr_FR' => 'Ecommerce',
                ],
            ],
            [
                'code' => 'paper',
                'category_tree' => '2014_collection',
                'currencies' => ['EUR'],
                'locales' => [
                    'fr_FR',
                    'en_US',
                    'de_DE',
                ],
            ],
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'code'        => 'ecommerce',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'code'        => 'paper',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getChannelApi();
        $response = $api->upsertList([
            [
                'labels' => [
                    'en_US' => 'Ecommerce',
                    'fr_FR' => 'Ecommerce',
                ],
            ],
            [
                'code' => 'mobile',
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
                    'fr_FR' => 'Course',
                ],
            ],
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'status_code' => 422,
            'message'     => 'Code is missing.',
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'status_code' => 413,
            'message' => 'Line is too long.',
        ], $responseLines[2]);
    }
}
