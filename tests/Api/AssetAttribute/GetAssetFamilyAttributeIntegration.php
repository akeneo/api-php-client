<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttribute;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApi;
use Akeneo\Pim\ApiClient\Exception\NotFoundHttpException;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetAssetFamilyAttributeIntegration extends ApiTestCase
{
    public function test_get_asset_family_attribute()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetAttributeApi::ASSET_ATTRIBUTE_URI, 'packshot', 'media_preview'),
            new ResponseStack(
                new Response($this->getPackshotPreviewAttribute(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetAttributeApi();
        $familyAttribute = $api->get('packshot', 'media_preview');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($familyAttribute, json_decode($this->getPackshotPreviewAttribute(), true));
    }

    public function test_get_unknown_asset_family_attribute()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetAttributeApi::ASSET_ATTRIBUTE_URI, 'packshot', 'foo'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Resource `foo` does not exist."}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetAttributeApi();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource `foo` does not exist.');

        $api->get('packshot', 'foo');
    }

    private function getPackshotPreviewAttribute(): string
    {
        return <<<JSON
            {
                "code": "media_preview",
                "labels": {
                    "en_US": "Media preview",
                    "fr_FR": "Aperçu du média"
                },
                "type": "media_link",
                "value_per_locale": false,
                "value_per_channel": false,
                "is_required_for_completeness": false,
                "prefix": "dam.com/my_assets/",
                "suffix": null,
                "media_type": "image"
            }
JSON;
    }
}
