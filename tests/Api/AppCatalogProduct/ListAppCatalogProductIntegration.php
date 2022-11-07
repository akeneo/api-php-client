<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AppCatalogProduct;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ListAppCatalogProductIntegration extends ApiTestCase
{
    // Refaire avec l'exemple dans la doc Octopus

    public function test_list_per_page()
    {
        $aCatalogId = '12351d98-200e-4bbc-aa19-7fdda1bd14f2';

        $this->server->setResponseOfPath(
            sprintf(AppCatalogProductApi::APP_CATALOG_PRODUCT_URI, $aCatalogId),
            new ResponseStack(
                new Response($this->getFirstPage(), [], HttpClient::HTTP_OK),
                new Response($this->getSecondPage(), [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogProductApi();
        $recordCursor = $api->all($aCatalogId);
        $records = iterator_to_array($recordCursor);

        Assert::assertCount(4, $records);
    }

    private function getFirstPage(): string
    {
        $baseUri = $this->server->getServerRoot();

        return <<<JSON
{
      "_links": {
        "self": {
          "href": "$baseUri/api/rest/v1/catalogs/12351d98-200e-4bbc-aa19-7fdda1bd14f2/product-uuids"
        },
        "first": {
          "href": "$baseUri/api/rest/v1/catalogs/12351d98-200e-4bbc-aa19-7fdda1bd14f2/product-uuids"
        },
        "next": {
          "href": "$baseUri/api/rest/v1/catalogs/12351d98-200e-4bbc-aa19-7fdda1bd14f2/product-uuids?search_after=eddfbd2a-abc7-488d-b9e3-41289c824f80"
        }
      },
      "_embedded": {
        "items": [
          "844c736b-a19b-48a6-a354-6056044729f0",
          "b2a683ef-4a91-4ed3-b3fa-76dab065a8d5",
          "eddfbd2a-abc7-488d-b9e3-41289c824f80"
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
          "href": "$baseUri/api/rest/v1/catalogs/12351d98-200e-4bbc-aa19-7fdda1bd14f2/product-uuids?search_after=eddfbd2a-abc7-488d-b9e3-41289c824f80"
        },
        "first": {
          "href": "$baseUri/api/rest/v1/catalogs/12351d98-200e-4bbc-aa19-7fdda1bd14f2/product-uuids"
        }
      },
      "_embedded": {
        "items": [
          "eddfbd2a-abc7-488d-b9e3-41289c824fff"
        ]
      }
    }
JSON;
    }
}
