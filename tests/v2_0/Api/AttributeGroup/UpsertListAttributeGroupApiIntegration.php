<?php

namespace Akeneo\Pim\tests\v2_0\Api\AttributeGroup;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListAttributeGroupApiIntegration extends ApiTestCase
{
    public function testUpsertListFromArraySuccessful()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        $response = $api->upsertList([
            [
                'code'       => 'info',
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'sort_order' => 1,
                'labels'     => [
                    'en_US' => 'Product information',
                ],
            ],
            [
                'code'   => 'tech',
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'labels'     => [
                    'en_US' => 'Tech',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'info',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'tech',
                'status_code' => 201,
            ]
        ], $responseLines);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
            <<<JSON
{"code":"info","labels":{"en_US":"Product information"}}
{"code":"tech","labels":{"en_US":"Tech"}}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getAttributeGroupApi();
        $response = $api->upsertList($streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'info',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'tech',
                'status_code' => 201,
            ]
        ], $responseLines);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        $response = $api->upsertList([
            [
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'sort_order' => 1,
                'labels'     => [
                    'en_US' => 'Product information',
                ],
            ],
            [
                'code'   => 'tech',
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
                    'fr_FR' => 'Tech',
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
