<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AssetFamily;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllAssetFamiliesIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . AssetFamilyApi::ASSET_FAMILIES_URI,
            new ResponseStack(
                new Response($this->getFirstPage(), [], 200),
                new Response($this->getSecondPage(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetFamilyApi();
        $assetFamilyCursor = $api->all();
        $assetFamilies = iterator_to_array($assetFamilyCursor);

        Assert::assertCount(3, $assetFamilies);
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links": {
                "self": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families"
                },
                "next": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families?search_after=packshot"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/notice"
                            }
                        },
                        "code": "notice",
                        "labels": {
                            "en_US": "Notices"
                        }
                    },
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot"
                            }
                        },
                        "code": "packshot",
                        "labels": {
                            "en_US": "Packshots"
                        }
                    }
                ]
            }
        }
JSON;
    }

    private function getSecondPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links": {
                "self": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families?search_after=packshot"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/video_presentation"
                            }
                        },
                        "code": "video_presentation",
                        "labels": {
                            "en_US": "Video Presentation"
                        }
                    }
                ]
            }
        }
JSON;
    }
}
