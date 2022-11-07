<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttributeOption;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertAssetFamilyAttributeOptionIntegration extends ApiTestCase
{
    public function test_upsert_asset_family_attribute_option()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetAttributeOptionApi::ASSET_ATTRIBUTE_OPTION_URI,
                'packshot',
                'wearing_model_size',
                'size_27'
            ),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $assetFamilyAttributeOption = [
            "code" => "size_27",
            "labels" => [
                "en_US" => "Size 27",
                "fr_FR" => "Taille 36"
            ]
        ];

        $api = $this->createClientByPassword()->getAssetAttributeOptionApi();
        $response = $api->upsert('packshot', 'wearing_model_size', 'size_27', $assetFamilyAttributeOption);

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            json_encode($assetFamilyAttributeOption)
        );
        Assert::assertSame(204, $response);
    }
}
