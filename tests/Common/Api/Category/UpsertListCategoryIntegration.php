<?php

namespace Akeneo\Pim\tests\Common\Api\Category;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListCategoryIntegration extends ApiTestCase
{
    public function testUpsertListFromArraySuccessful()
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

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'sandals',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'booties',
                'status_code' => 201,
            ]
        ], $responseLines);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
<<<JSON
{"code":"sandals","parent":"winter_collection","labels":{"en_US":"Sandals","fr_FR":"Sandales"}}
{"code":"booties","parent":"summer_collection","labels":{"en_US":"Booties","fr_FR":"Bottines"}}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getCategoryApi();
        $response = $api->upsertList($streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'sandals',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'booties',
                'status_code' => 201,
            ]
        ], $responseLines);
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

        $this->assertSame([
            1 => [
                'line'        => 1,
                'status_code' => 422,
                'message'     => 'Code is missing.',
            ],
            2 => [
                'line'        => 2,
                'status_code' => 413,
                'message'     => 'Line is too long.',
            ]
        ], $responseLines);
    }
}
