<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\Asset;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllAssetsIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetApi::ASSETS_URI, 'packshot'),
            new ResponseStack(
                new Response($this->getFirstPage(), [], 200),
                new Response($this->getSecondPage(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getAssetManagerApi();
        $assetCursor = $api->all('packshot');
        $assets = iterator_to_array($assetCursor);

        Assert::assertCount(3, $assets);
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links": {
                "self": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets"
                },
                "next": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets?search_after=golden_table"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets\/yellow_table"
                            }
                        },
                        "code": "yellow_table",
                        "values": {
                            "description": [
                                {
                                    "locale": null,
                                    "channel": null,
                                    "data": "A yellow table."
                                }
                            ]
                        }
                    },
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets\/golden_table"
                            }
                        },
                        "code": "golden_table",
                        "values": {
                            "description": [
                                {
                                    "locale": null,
                                    "channel": null,
                                    "data": "A golden table."
                                }
                            ]
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
                    "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets?search_after=golden_table"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/asset-families\/packshot\/assets\/green_table"
                            }
                        },
                        "code": "green_table",
                        "values": {
                            "description": [
                                {
                                    "locale": null,
                                    "channel": null,
                                    "data": "A green table."
                                }
                            ]
                        }
                    }
                ]
            }
        }
JSON;
    }
}
