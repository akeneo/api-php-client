<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\Asset;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertListAssetIntegration extends ApiTestCase
{
    public function test_upsert_a_list_of_assets()
    {
        $responseBody = <<<JSON
        [
          {
            "code": "sku_54628_telescope",
            "status_code": 204
          },
          {
            "code": "sku_45689_dobson",
            "status_code": 201
          }
        ]
JSON;

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetApi::ASSETS_URI, 'packshot'),
            new ResponseStack(
                new Response($responseBody, [], 200)
            )
        );

        $assets = [
            [
                "code" => "sku_54628_telescope",
                "values" => [
                    "media_preview" => [
                        [
                            "locale" => null,
                            "channel" => null,
                            "data" => "sku_54628_picture1.jpg"
                        ]
                    ],
                    "photographer" => [
                        [
                            "locale" => null,
                            "channel" => null,
                            "data" => "ben_levy"
                        ]
                    ]
                ]
            ],
            [
                "code" => "sku_45689_dobson",
                "values" => [
                    "media_preview" => [
                        [
                            "locale" => null,
                            "channel" => null,
                            "data" => "sku_45689_dobson_pic1.jpg"
                        ]
                    ],
                    "photographer" => [
                        [
                            "locale" => null,
                            "channel" => null,
                            "data" => "ben_levy"
                        ]
                    ]
                ]
            ]
        ];

        $expectedResponses = [
            [
                'code' => 'sku_54628_telescope',
                'status_code' => 204
            ],
            [
                'code' => 'sku_45689_dobson',
                'status_code' => 201
            ],
        ];

        $api = $this->createClientByPassword()->getAssetManagerApi();
        $responses = $api->upsertList('packshot', $assets);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($assets));
        Assert::assertSame($expectedResponses, $responses);
    }
}
