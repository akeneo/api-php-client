<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class UpsertListProductApiIntegration extends AbstractProductApiTestCase
{
    public function testUpsertListFromArraySuccessful()
    {
        $api = $this->createClient()->getProductApi();

        $response = $api->upsertList([
            [
                'identifier' => 'docks_black',
                'enabled'    => false,
                'values'     => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope'  => null,
                            'data'   => 'Black Docks',
                        ],
                    ],
                ]
            ],
            [
                'identifier' => 'pumps',
                'enabled'    => false,
                'family'     => 'sandals',
                'categories' => ['summer_collection'],
                'values'     => [
                    'name' => [
                        [
                            'data'   => 'The pumps',
                            'locale' => 'en_US',
                            'scope'  => null,
                        ],
                        [
                            'data'   => 'Les pumps',
                            'locale' => 'fr_FR',
                            'scope'  => null,
                        ]
                    ],
                ]
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'identifier'  => 'docks_black',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'identifier'  => 'pumps',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
<<<JSON
{"identifier":"docks_black","enabled":false,"values":{"name":[{"locale":"en_US","scope":null,"data":"Black Docks"}]}}
{"identifier":"pumps","enabled":false,"family":"sandals","categories":["summer_collection"],"values":{"name":[{"data":"The pumps","locale":"en_US","scope":null},{"data":"Les pumps","locale":"fr_FR","scope":null}]}}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getProductAPi();
        $response = $api->upsertList($streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'identifier'  => 'docks_black',
            'status_code' => 204,
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'identifier'  => 'pumps',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getProductApi();

        $response = $api->upsertList([
            [
                'enabled'    => false,
                'values'     => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope'  => null,
                            'data'   => 'Black Docks',
                        ],
                    ],
                ]
            ],
            [
                'identifier' => 'pumps',
                'enabled'    => false,
                'family'     => 'sandals',
                'categories' => ['summer_collection'],
                'values'     => [
                    'name' => [
                        [
                            'data'   => 'line too long' . str_repeat('a', 1000000),
                            'locale' => 'en_US',
                            'scope'  => null,
                        ]
                    ],
                ]
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        $this->assertCount(2, $responseLines);

        $this->assertSame([
            'line'        => 1,
            'status_code' => 422,
            'message'     => 'Identifier is missing.',
        ], $responseLines[1]);

        $this->assertSame([
            'line'        => 2,
            'status_code' => 413,
            'message'     => 'Line is too long.',
        ], $responseLines[2]);
    }
}
