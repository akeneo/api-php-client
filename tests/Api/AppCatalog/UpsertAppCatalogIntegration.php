<?php
declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class UpsertAppCatalogIntegration extends ApiTestCase
{
    public function test_upsert_catalog()
    {
        $aCatalogId = '12351d98-200e-4bbc-aa19-7fdda1bd14f2';
        $someCatalogData = ['name' => 'A catalog name'];

        $this->server->setResponseOfPath(
            '/'.sprintf(AppCatalogApi::APP_CATALOG_URI, $aCatalogId),
            new ResponseStack(
                new Response('', [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $response = $api->upsert($aCatalogId, $someCatalogData);

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            json_encode($someCatalogData)
        );
        Assert::assertSame(HttpClient::HTTP_OK, $response);
    }
}