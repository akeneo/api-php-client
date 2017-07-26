<?php

namespace Akeneo\Pim\tests\Common\Api\Family;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListFamilyApiIntegration extends ApiTestCase
{
    public function testUpsertListSuccessful()
    {
        $api = $this->createClient()->getFamilyApi();

        $response = $api->upsertList([
            [
                'code'       => 'heels',
                'attributes' => [
                    'manufacturer',
                    'name',
                    'price',
                    'sku',
                ],
                'attribute_as_label' => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'name',
                        'price',
                        'sku',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Heels!',
                    'fr_FR' => 'Talons!',
                ],
            ],
            [
                'code'       => 'running',
                'attributes' => [
                    'color',
                    'description',
                    'manufacturer',
                    'name',
                ],
                'attribute_as_label'     => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'description',
                        'name',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Running',
                    'fr_FR' => 'Course',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'code'        => 'heels',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'code'        => 'running',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getFamilyApi();

        $response = $api->upsertList([
            [
                'attributes' => [
                    'color',
                    'description',
                    'manufacturer',
                    'name',
                ],
                'attribute_as_label'     => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'description',
                        'name',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Running',
                    'fr_FR' => 'Course',
                ],
            ],
            [
                'code'       => 'running',
                'attributes' => [
                    'color',
                    'description',
                    'manufacturer',
                    'name',
                ],
                'attribute_as_label'     => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'description',
                        'name',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
                    'fr_FR' => 'Course',
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
