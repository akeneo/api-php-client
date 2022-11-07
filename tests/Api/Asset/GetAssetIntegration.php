<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\Asset;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetAssetIntegration extends ApiTestCase
{
    public function test_get_asset()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetApi::ASSET_URI, 'packshot', 'battleship'),
            new ResponseStack(
                new Response($this->getAsset(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetManagerApi();
        $asset = $api->get('packshot', 'battleship');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($asset, json_decode($this->getAsset(), true));
    }

    public function test_get_unknown_asset()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetApi::ASSET_URI, 'packshot', 'peace-sheep'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Asset \"peace-sheep\" does not exist."}', [], 404)
            )
        );

        $this->expectException(\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException::class);
        $this->expectExceptionMessage('Asset "peace-sheep" does not exist.');

        $api = $this->createClientByPassword()->getAssetManagerApi();
        $api->get('packshot', 'peace-sheep');
    }

    private function getAsset(): string
    {
        return <<<JSON
            {
                "_links": {
                    "self": {
                        "href": "https:\/\/demo.akeneo.com\/api\/rest\/v1\/asset-families\/packshot\/assets\/battleship"
                    }
                },
                "code": "battleship",
                "values": {
                    "description": [
                        {
                            "locale": null,
                            "channel": null,
                            "data": "A wonderful battle sheep."
                        }
                    ]
                }
            }
JSON;
    }
}
