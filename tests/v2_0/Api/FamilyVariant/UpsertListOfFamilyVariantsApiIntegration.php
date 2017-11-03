<?php

namespace Akeneo\Pim\tests\v2_0\Api\FamilyVariant;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListOfFamilyVariantsApiIntegration extends ApiTestCase
{
    public function testUpsertListFromArraySuccessful()
    {
        $api = $this->createClient()->getFamilyVariantApi();

        $response = $api->upsertList('boots', [
            [
                'code' => 'rain_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'test update',
                    'fr_FR' => 'test update'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => [
                            'name',
                            'description',
                            'side_view',
                            'color'
                        ]
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size']
                    ]
                ]
            ],
            [
                'code' => 'man_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'test fail',
                    'fr_FR' => 'test fail'
                ],
                'variant_attribute_sets' => []
            ],
            [
                'code' => 'create_boots_color_size',
                'labels' => [
                    'de_DE' => 'Stiefel nach Farbe und Größe',
                    'en_US' => 'test create',
                    'fr_FR' => 'test create'
                ],
                'variant_attribute_sets' => [
                    [
                        'level' => 1,
                        'axes' => ['color'],
                        'attributes' => [
                            'name',
                            'description',
                            'side_view',
                            'color'
                        ]
                    ],
                    [
                        'level' => 2,
                        'axes' => ['size'],
                        'attributes' => ['sku', 'size']
                    ]
                ]
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'rain_boots_color_size',
                'status_code' => 204,
            ],
            2 => [
                'line' => 2,
                'code' => 'man_boots_color_size',
                'status_code' => 422,
                'message' => 'Validation failed.',
                'errors' => [
                    [
                        'property' => '',
                        'message' => 'There should be at least one level defined in the family variant'
                    ]
                ]
            ],
            3 => [
                'line'        => 3,
                'code'        => 'create_boots_color_size',
                'status_code' => 201,
            ],
        ], $responseLines);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
            <<<JSON
{"code":"rain_boots_color_size","labels":{"de_DE":"Stiefel nach Farbe und Gr\u00f6\u00dfe","en_US":"test update","fr_FR":"test update"},"variant_attribute_sets":[{"level":1,"axes":["color"],"attributes":["name","description","side_view","color"]},{"level":2,"axes":["size"],"attributes":["sku","size"]}]}
{"code":"man_boots_color_size","labels":{"de_DE":"Stiefel nach Farbe und Gr\u00f6\u00dfe","en_US":"test update","fr_FR":"test update"},"variant_attribute_sets":[{"level":1,"axes":["color"],"attributes":["name","description","side_view","color"]},{"level":2,"axes":["size"],"attributes":["sku","size"]}]}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getFamilyVariantApi();
        $response = $api->upsertList('boots', $streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'rain_boots_color_size',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'man_boots_color_size',
                'status_code' => 204,
            ],
        ], $responseLines);
    }
}
