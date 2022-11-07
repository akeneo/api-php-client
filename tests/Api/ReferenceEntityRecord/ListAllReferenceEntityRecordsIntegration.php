<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntityRecord;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListAllReferenceEntityRecordsIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORDS_URI, 'designer'),
            new ResponseStack(
                new Response($this->getFirstPage(), [], 200),
                new Response($this->getSecondPage(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getReferenceEntityRecordApi();
        $recordCursor = $api->all('designer');
        $records = iterator_to_array($recordCursor);

        Assert::assertCount(3, $records);
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links": {
                "self": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records"
                },
                "next": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records?search_after=dyson"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records\/arad"
                            }
                        },
                        "code": "arad",
                        "values": {
                            "label": [
                                {
                                    "locale": "en_US",
                                    "channel": null,
                                    "data": "Ron Arad"
                                }
                            ]
                        }
                    },
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records\/dyson"
                            }
                        },
                        "code": "dyson",
                        "values": {
                            "label": [
                                {
                                    "locale": "en_US",
                                    "channel": null,
                                    "data": "James Dyson"
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
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records?search_after=dyson"
                },
                "first": {
                    "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records"
                }
            },
            "_embedded": {
                "items": [
                    {
                        "_links": {
                            "self": {
                                "href": "$baseUri\/api\/rest\/v1\/reference-entities\/designer\/records\/starck"
                            }
                        },
                        "code": "starck",
                        "values": {
                            "label": [
                                {
                                    "locale": "en_US",
                                    "channel": null,
                                    "data": "Philippe Starck"
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
