<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntity;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllReferenceEntitiesIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . ReferenceEntityApi::REFERENCE_ENTITIES_URI,
            new ResponseStack(
                new Response($this->getFirstPage(), [], 200),
                new Response($this->getSecondPage(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getReferenceEntityApi();
        $referenceEntityCursor = $api->all();
        $referenceEntities = iterator_to_array($referenceEntityCursor);

        Assert::assertCount(3, $referenceEntities);
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links": {
                "self": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities"
                },
                "next": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities?search_after=designer"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/brand"
                            },
                            "image_download": {
                                "href": "https://demo.akeneo.com/api/rest/v1/reference-entities-media-files/0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                            }
                        },
                        "code": "brand",
                        "labels": {
                            "en_US": "Brand"
                        },
                        "image": "0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                    },
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer"
                            },
                            "image_download": {
                                "href": "https://demo.akeneo.com/api/rest/v1/reference-entities-media-files/0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                            }
                        },
                        "code": "designer",
                        "labels": {
                            "en_US": "Designer"
                        },
                        "image": "0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
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
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities?search_after=designer"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/color"
                            },
                            "image_download": {
                                "href": "https://demo.akeneo.com/api/rest/v1/reference-entities-media-files/0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                            }
                        },
                        "code": "color",
                        "labels": {
                            "en_US": "Color"
                        },
                        "image": "0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                    }
                ]
            }
        }
JSON;
    }
}
