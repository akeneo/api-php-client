<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Attribute;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class UpsertListAttributeIntegration extends ApiTestCase
{
    public function testUpsertListSuccessful()
    {
        $api = $this->createClient()->getAttributeApi();

        $response = $api->upsertList([
            [
                'code'                   => 'name',
                'max_characters'         => 42,
                'useable_as_grid_filter' => true,
            ],
            [
                'code'        => 'comment',
                'type'        => 'pim_catalog_text',
                'group'       => 'other',
                'unique'      => false,
                'localizable' => true,
                'scopable'    => false,
                'labels'      => [
                    'en_US' => 'Comment',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'code'        => 'name',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'code'        => 'comment',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getAttributeApi();

        $response = $api->upsertList([
            [
                'max_characters'         => 42,
                'useable_as_grid_filter' => true,
            ],
            [
                'code'        => 'comment',
                'type'        => 'pim_catalog_text',
                'group'       => 'other',
                'unique'      => false,
                'localizable' => true,
                'scopable'    => false,
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
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
