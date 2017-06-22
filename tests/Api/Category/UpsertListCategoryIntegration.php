<?php

namespace Akeneo\Pim\tests\Api\Category;

use Akeneo\Pim\tests\Api\ApiTestCase;

class UpsertListCategoryIntegration extends ApiTestCase
{
    public function testUpsertListSuccessful()
    {
        $api = $this->createClient()->getCategoryApi();

        $response = $api->upsertList([
            [
                'code'   => 'sandals',
                'parent' => 'winter_collection',
                'labels' => [
                    'en_US' => 'Sandals',
                    'fr_FR' => 'Sandales',
                ],
            ],
            [
                'code'   => 'booties',
                'parent' => 'summer_collection',
                'labels' => [
                    'en_US' => 'Booties',
                    'fr_FR' => 'Bottines',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'code'        => 'sandals',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'code'        => 'booties',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getCategoryApi();

        $response = $api->upsertList([
            [
                'parent' => 'winter_collection',
                'labels' => [
                    'en_US' => 'Sandals',
                    'fr_FR' => 'Sandales',
                ],
            ],
            [
                'code'   => 'booties',
                'parent' => 'summer_collection',
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
                    'fr_FR' => 'Bottines',
                ],
            ]
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
