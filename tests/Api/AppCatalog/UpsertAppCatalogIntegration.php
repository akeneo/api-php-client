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
        $catalogId = '12351d98-200e-4bbc-aa19-7fdda1bd14f2';
        $catalogData = ['name' => 'A catalog name'];
        $expectedJson = <<<JSON
{
    "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f2",
     "name": "A catalog name",
     "enabled": false
}
JSON;

        $this->server->setResponseOfPath(
            '/' . sprintf(AppCatalogApi::APP_CATALOG_URI, $catalogId),
            new ResponseStack(
                new Response($expectedJson, [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $response = $api->upsert($catalogId, $catalogData);

        Assert::assertSame(json_decode($expectedJson, true), $response);
    }
}
