<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\ProductModel;

use Akeneo\Pim\ApiClient\tests\Common\Api\Product\AbstractProductApiTestCase;

class UpsertListOfProductModelApiIntegration extends AbstractProductApiTestCase
{
    public function testUpsertListFromArraySuccessful()
    {
        $api = $this->createClient()->getProductModelApi();

        $response = $api->upsertList([
            [
                'code' => 'rain_boots_red',
                'family_variant' => 'rain_boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots'],
                'values' => [
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Red rain boots made of butter (why not?) for winter.'
                        ]
                    ]
                ]
            ],
            [
                'code' => 'rain_boots_saddle',
                'family_variant' => 'rain_boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots'],
                'values' => [
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Saddle rain boots made of rubber for winter.'
                        ]
                    ]
                ]
            ],
            [
                'code' => 'rain_boots_greem',
                'family_variant' => 'rain_boots_color_size',
                'parent' => 'rain_boots',
                'categories' => ['2014_collection', 'winter_boots'],
                'values' => [
                    'description' => [
                        [
                            'locale' => 'en_US',
                            'scope' => 'ecommerce',
                            'data' => 'Greem rain boots made of rubber for winter.'
                        ]
                    ],
                    'color' => [
                        [
                            'locale' => null,
                            'scope' => null,
                            'data' => 'greem'
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'rain_boots_red',
                'status_code' => 204,
            ],
            2 => [
                'line' => 2,
                'code' => 'rain_boots_saddle',
                'status_code' => 422,
                'message' => 'Validation failed.',
                'errors' => [
                    [
                        'property' => 'attribute',
                        'message' => 'Attribute "color" cannot be empty, as it is defined as an axis for this entity'
                    ]
                ]
            ],
            3 => [
                'line'        => 3,
                'code'        => 'rain_boots_greem',
                'status_code' => 201,
            ],
        ], $responseLines);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
            <<<JSON
{"code":"rain_boots_red","family_variant":"rain_boots_color_size","parent":"rain_boots","categories":["2014_collection"],"values":{"description":[{"locale":"en_US","scope":"ecommerce","data":"Red rain boots."}]}}
{"code":"rain_boots_blue","family_variant":"rain_boots_color_size","parent":"rain_boots","categories":["2014_collection"],"values":{"description":[{"locale":"en_US","scope":"ecommerce","data":"Blue rain boots."}]}}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getProductModelApi();
        $response = $api->upsertList($streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'rain_boots_red',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'rain_boots_blue',
                'status_code' => 204,
            ],
        ], $responseLines);
    }
}
