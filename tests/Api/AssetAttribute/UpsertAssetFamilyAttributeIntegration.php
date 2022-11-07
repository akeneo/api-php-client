<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttribute;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertAssetFamilyAttributeIntegration extends ApiTestCase
{
    public function test_upsert_asset_family_attribute()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetAttributeApi::ASSET_ATTRIBUTE_URI, 'packshot', 'media_preview'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $assetFamilyAttribute = [
            'code' => 'media_preview',
            'labels' => [
                'en_US' => 'Media Preview'
            ],
            'type' => 'media_link',
            "value_per_locale" => false,
            "value_per_channel" => false,
            "is_required_for_completeness" => false,
            "prefix" => "dam.com/my_assets/",
            "suffix" => null,
            "media_type" => "image"
        ];

        $api = $this->createClientByPassword()->getAssetAttributeApi();
        $response = $api->upsert('packshot', 'media_preview', $assetFamilyAttribute);

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            json_encode($assetFamilyAttribute)
        );
        Assert::assertSame(204, $response);
    }
}
