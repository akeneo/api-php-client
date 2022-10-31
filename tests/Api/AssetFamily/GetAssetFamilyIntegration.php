<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetFamily;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetAssetFamilyIntegration extends ApiTestCase
{
    public function test_get_asset_family()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(AssetFamilyApi::ASSET_FAMILY_URI, 'packshot'),
            new ResponseStack(
                new Response($this->getPackshot(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetFamilyApi();
        $product = $api->get('packshot');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($product, json_decode($this->getPackshot(), true));
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     * @expectedExceptionMessage Asset family "foo" does not exist.
     */
    public function test_get_unknown_asset_family()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(AssetFamilyApi::ASSET_FAMILY_URI, 'foo'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Asset family \"foo\" does not exist."}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetFamilyApi();
        $api->get('foo');
    }

    private function getPackshot(): string
    {
        return <<<JSON
            {
                "code": "packshot",
                "labels": {
                    "en_US": "Packshots"
                }
            }
JSON;
    }
}
