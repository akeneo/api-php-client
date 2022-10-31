<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttributeOption;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllAssetFamilyAttributeOptionsIntegration extends ApiTestCase
{
    public function test_list_family_attribute_options()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetAttributeOptionApi::ASSET_ATTRIBUTE_OPTIONS_URI,
                'packshot',
                'wearing_model_size'
            ),
            new ResponseStack(
                new Response($this->getAssetFamilyAttributeOptions(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetAttributeOptionApi();
        $assetFamilyAttributeOptions = $api->all('packshot', 'wearing_model_size');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($assetFamilyAttributeOptions, json_decode($this->getAssetFamilyAttributeOptions(), true));
    }

    private function getAssetFamilyAttributeOptions(): string
    {
        return <<<JSON
        [
            {
                "code": "size_27",
                "labels": {
                    "en_US": "Size 27",
                    "fr_FR": "Taille 36"
                }
            },
            {
                "code": "small",
                "labels": {
                    "en_US": "S",
                    "fr_FR": "S"
                }
            },
            {
                "code": "medium",
                "labels": {
                    "en_US": "M",
                    "fr_FR": "M"
                }
            }
        ]
JSON;
    }
}
