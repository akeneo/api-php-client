<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ProductUuid;

use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ListPerPageProductUuidIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . ProductUuidApi::PRODUCTS_UUID_URI,
            new ResponseStack(
                new Response($this->getFirstPage(), [], HttpClient::HTTP_OK),
                new Response($this->getSecondPage(), [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getProductUuidApi();
        $firstPage = $api->listPerPage(10, true, []);

        Assert::assertSame(
            ['limit' => '10', 'with_count' => 'true'],
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_GET]
        );

        Assert::assertInstanceOf(PageInterface::class, $firstPage);
        Assert::assertEquals(11, $firstPage->getCount());
        Assert::assertNull($firstPage->getPreviousLink());
        Assert::assertNull($firstPage->getPreviousPage());
        Assert::assertFalse($firstPage->hasPreviousPage());
        Assert::assertTrue($firstPage->hasNextPage());
        Assert::assertSame(
            $this->server->getServerRoot() . '/api/rest/v1/products-uuid?page=2&with_count=true&pagination_type=page&limit=10',
            $firstPage->getNextLink()
        );
        Assert::assertCount(10, $firstPage->getItems());

        $secondPage = $firstPage->getNextPage();

        Assert::assertSame([
            'page' => '2',
            'with_count' => 'true',
            'pagination_type' => 'page',
            'limit' => '10'
        ], $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_GET]);

        Assert::assertInstanceOf(PageInterface::class, $secondPage);
        Assert::assertEquals(11, $secondPage->getCount());
        Assert::assertNull($secondPage->getNextLink());
        Assert::assertNull($secondPage->getNextPage());
        Assert::assertFalse($secondPage->hasNextPage());
        Assert::assertTrue($secondPage->hasPreviousPage());
        Assert::assertSame(
            $this->server->getServerRoot() . '/api/rest/v1/products-uuid?page=1&with_count=true&pagination_type=page&limit=10',
            $secondPage->getPreviousLink()
        );
        Assert::assertCount(1, $secondPage->getItems());
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links":{
                "self":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "first":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "next":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=2&with_count=true&pagination_type=page&limit=10"
                }
            },
            "current_page": 1,
            "items_count": 11,
            "_embedded": {
                "items": [
                  {
                    "_links":{
                      "self":{
                        "href": "$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f3"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f3",
                    "identifier":"big_boot",
                    "family":"boots",
                    "groups":[
                      "similar_boots"
                    ],
                    "categories":[
                      "summer_collection",
                      "winter_boots",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"black"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href": "$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f4"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f4",
                    "identifier":"docks_red",
                    "family":"boots",
                    "groups":[
                      "caterpillar_boots"
                    ],
                    "categories":[
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"red"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f5"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f5",
                    "identifier":"small_boot",
                    "family":"boots",
                    "groups":[
                      "similar_boots"
                    ],
                    "categories":[
                      "summer_collection",
                      "winter_boots",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"maroon"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f6"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f6",
                    "identifier":"medium_boot",
                    "family":"boots",
                    "groups":[
                      "similar_boots"
                    ],
                    "categories":[
                      "winter_boots",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"white"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f7"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f7",
                    "identifier":"dance_shoe",
                    "family":"sandals",
                    "groups":[
                      
                    ],
                    "categories":[
                      "sandals"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"greem"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f8"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f8",
                    "identifier":"black_sneakers",
                    "family":"sneakers",
                    "groups":[
                      
                    ],
                    "categories":[
                      "summer_collection",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"black"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14f9"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f9",
                    "identifier":"docks_blue",
                    "family":"boots",
                    "groups":[
                      "caterpillar_boots"
                    ],
                    "categories":[
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"blue"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14a1"
                      }
                    },
                    "uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14a1",
                    "identifier":"docks_black",
                    "family":"boots",
                    "groups":[
                      "caterpillar_boots"
                    ],
                    "categories":[
                      "winter_boots",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"black"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14a2"
                      }
                    },
                    "uuid":"12951d98-210e-4bRC-ab18-7fdgf1bd14a2",
                    "identifier":"docks_white",
                    "family":"boots",
                    "groups":[
                      "caterpillar_boots"
                    ],
                    "categories":[
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"white"
                        }
                      ]
                    }
                  },
                  {
                    "_links":{
                      "self":{
                        "href":"$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14a3"
                      }
                    },
                    "uuid":"12951d98-210e-4bRC-ab18-7fdgf1bd14a3",
                    "identifier":"docks_maroon",
                    "family":"boots",
                    "groups":[
                      "caterpillar_boots"
                    ],
                    "categories":[
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"maroon"
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
            "_links":{
                "self":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=2&with_count=true&pagination_type=page&limit=10"
                },
                "first":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "previous":{
                  "href": "$baseUri\/api\/rest\/v1\/products-uuid?page=1&with_count=true&pagination_type=page&limit=10"
                }
            },
            "current_page": 2,
            "items_count": 11,
            "_embedded": {
                "items": [
                  {
                    "_links":{
                      "self":{
                        "href": "$baseUri\/api\/rest\/v1\/products-uuid\/12951d98-210e-4bRC-ab18-7fdgf1bd14a4"
                      }
                    },
                    "uuid":"12951d98-210e-4bRC-ab18-7fdgf1bd14a4",
                    "identifier":"big_nordic_boot",
                    "family":"boots",
                    "groups":[
                      "similar_boots"
                    ],
                    "categories":[
                      "summer_collection",
                      "winter_boots",
                      "winter_collection"
                    ],
                    "enabled":true,
                    "values":{
                      "color":[
                        {
                          "locale":null,
                          "scope":null,
                          "data":"black"
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
