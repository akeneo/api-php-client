<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetAttribute;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllAssetFamilyAttributesIntegration extends ApiTestCase
{
    public function test_list_family_attributes()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetAttributeApi::ASSET_ATTRIBUTES_URI, 'packshot'),
            new ResponseStack(
                new Response($this->getAssetFamilyAttributes(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetAttributeApi();
        $assetFamilyAttributes = $api->all('packshot');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($assetFamilyAttributes, json_decode($this->getAssetFamilyAttributes(), true));
    }

    private function getAssetFamilyAttributes(): string
    {
        return <<<JSON
        [
            {
                "code": "model_is_wearing_size",
                "labels": {
                    "en_US": "Model is wearing size",
                    "fr_FR": "Le mannequin porte la taille"
                },
                "type": "single_option",
                "value_per_locale": false,
                "value_per_channel": false,
                "is_required_for_completeness": true
            },
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
            },
            {
                "code": "warning_mention",
                "labels": {
                    "en_US": "Warning mention",
                    "fr_FR": "Avertissement"
                },
                "type": "text",
                "value_per_locale": true,
                "value_per_channel": false,
                "is_required_for_completeness": false,
                "max_characters": 50,
                "is_textarea": false,
                "is_rich_text_editor": null,
                "validation_rule": null,
                "validation_regexp": null
            }
        ]
JSON;
    }
}
