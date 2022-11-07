<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class ListPerPageProductTest extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            '/' . ProductApi::PRODUCTS_URI,
            new ResponseStack(
                new Response($this->getFirstPage(), [], 200),
                new Response($this->getSecondPage(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getProductApi();
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
            $this->server->getServerRoot() . '/api/rest/v1/products?page=2&with_count=true&pagination_type=page&limit=10',
            $firstPage->getNextLink()
        );
        Assert::assertEquals(10, count($firstPage->getItems()));

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
            $this->server->getServerRoot() . '/api/rest/v1/products?page=1&with_count=true&pagination_type=page&limit=10',
            $secondPage->getPreviousLink()
        );
        Assert::assertEquals(1, count($secondPage->getItems()));
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
        {
            "_links":{
                "self":{
                  "href": "$baseUri\/api\/rest\/v1\/products?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "first":{
                  "href": "$baseUri\/api\/rest\/v1\/products?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "next":{
                  "href": "$baseUri\/api\/rest\/v1\/products?page=2&with_count=true&pagination_type=page&limit=10"
                }
            },
            "current_page": 1,
            "items_count": 11,
            "_embedded": {
                "items": [
                  {
                    "_links":{
                      "self":{
                        "href": "$baseUri\/api\/rest\/v1\/products\/big_boot"
                      }
                    },
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
                        "href": "$baseUri\/api\/rest\/v1\/products\/docks_red"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/small_boot"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/medium_boot"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/dance_shoe"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/black_sneakers"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/docks_blue"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/docks_black"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/docks_white"
                      }
                    },
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
                        "href":"$baseUri\/api\/rest\/v1\/products\/docks_maroon"
                      }
                    },
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
                  "href": "$baseUri\/api\/rest\/v1\/products?page=2&with_count=true&pagination_type=page&limit=10"
                },
                "first":{
                  "href": "$baseUri\/api\/rest\/v1\/products?page=1&with_count=true&pagination_type=page&limit=10"
                },
                "previous":{
                  "href": "$baseUri\/api\/rest\/v1\/products?page=1&with_count=true&pagination_type=page&limit=10"
                }
            },
            "current_page": 2,
            "items_count": 11,
            "_embedded": {
                "items": [
                  {
                    "_links":{
                      "self":{
                        "href": "$baseUri\/api\/rest\/v1\/products\/big_boot"
                      }
                    },
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
