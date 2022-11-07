<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ListAppCatalogIntegration extends ApiTestCase
{
    public function test_list_per_page()
    {
        $this->server->setResponseOfPath(
            sprintf('/%s', AppCatalogApi::APP_CATALOGS_URI),
            new ResponseStack(
                new Response($this->getFirstPage(), [], HttpClient::HTTP_OK),
                new Response($this->getSecondPage(), [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $recordCursor = $api->all();
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
          "href": "$baseUri/api/rest/v1/catalogs?page=1"
        },
        "first": {
          "href": "$baseUri/api/rest/v1/catalogs?page=1"
        },
        "previous": {
          "href": "$baseUri/api/rest/v1/catalogs?page=1"
        },
        "next": {
          "href": "$baseUri/api/rest/v1/catalogs?page=2"
        }
      },
      "current_page": 1,
      "_embedded": {
        "items": [
          {
            "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f2",
            "name": "Store FR",
            "enabled": false
          },
          {
            "id": "092c5f22-ecd8-485f-97e9-3b78098e1386",
            "name": "Store US",
            "enabled": true
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
          "href": "$baseUri/api/rest/v1/catalogs?page=2"
        },
        "first": {
          "href": "$baseUri/api/rest/v1/catalogs?page=1"
        },
        "previous": {
          "href": "$baseUri/api/rest/v1/catalogs?page=1"
        }
      },
      "current_page": 2,
      "_embedded": {
        "items": [
          {
            "id": "12951d98-210e-4bRC-ab18-7fdgf1bd14f3",
            "name": "Store GR",
            "enabled": true
          }
        ]
      }
    }
JSON;
    }
}
