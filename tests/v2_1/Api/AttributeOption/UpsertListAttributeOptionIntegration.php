<?php

namespace Akeneo\Pim\ApiClient\tests\v2_1\Api\AttributeOption;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class UpsertListAttributeOptionIntegration extends ApiTestCase
{
    public function testUpsertListSuccessful()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $response = $api->upsertList('weather_conditions', [
            [
                'code'       => 'hot',
                'attribute'  => 'weather_conditions',
                'sort_order' => 34,
                'labels'     => [
                    'en_US' => 'Hot!',
                ],
            ],
            [
                'code'       => 'cloudy',
                'attribute'  => 'weather_conditions',
                'sort_order' => 35,
                'labels'     => [
                    'en_US' => 'Cloudy',
                ],

            ],
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'code'        => 'hot',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'code'        => 'cloudy',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $response = $api->upsertList('weather_conditions', [
            [
                'attribute'  => 'weather_conditions',
                'sort_order' => 34,
                'labels'     => [
                    'en_US' => 'Hot!',
                ],
            ],
            [
                'code'       => 'cloudy!',
                'attribute'  => 'weather_conditions',
                'sort_order' => 35,
                'labels'     => [
                    'en_US' => 'Cloudy',
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
            'code'        => 'cloudy!',
            'status_code' => 422,
            'message' => 'Validation failed.',
            'errors' => [[
                'property' => 'code',
                'message' => 'Option code may contain only letters, numbers and underscores'
            ]]
        ], $responseLines[2]);
    }
}
