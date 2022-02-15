<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttributeOption;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetAssetFamilyAttributeOptionIntegration extends ApiTestCase
{
    public function test_get_asset_family_attribute_option()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(AssetAttributeOptionApi::ASSET_ATTRIBUTE_OPTION_URI, 'packshot', 'wearing_model_size', 'small'),
            new ResponseStack(
                new Response($this->getPackshotAttributeOption(), [], 200)
            )
        );

        $api = $this->createClient()->getAssetAttributeOptionApi();
        $familyAttributeOption = $api->get('packshot', 'wearing_model_size', 'small');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($familyAttributeOption, json_decode($this->getPackshotAttributeOption(), true));
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     * @expectedExceptionMessage Resource `XLS` does not exist.
     */
    public function test_get_unknown_asset_family_attribute_option()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(AssetAttributeOptionApi::ASSET_ATTRIBUTE_OPTION_URI, 'packshot', 'wearing_model_size', 'XLS'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Resource `XLS` does not exist."}', [], 404)
            )
        );

        $api = $this->createClient()->getAssetAttributeOptionApi();
        $api->get('packshot', 'wearing_model_size', 'XLS');
    }

    private function getPackshotAttributeOption(): string
    {
        return <<<JSON
            {
                "code": "small",
                "labels": {
                    "en_US": "S",
                    "fr_FR": "S"
                }
            }
JSON;
    }
}
