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
class CreateAppCatalogIntegration extends ApiTestCase
{
    public function test_create_catalog()
    {
        $catalogData = ['name' => 'A catalog name'];
        $expectedJson = <<<JSON
{
    "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f2",
     "name": "A catalog name",
     "enabled": false
}
JSON;

        $this->server->setResponseOfPath(
            '/' . AppCatalogApi::APP_CATALOGS_URI,
            new ResponseStack(
                new Response($expectedJson, [], HttpClient::HTTP_CREATED)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $response = $api->create($catalogData);

        Assert::assertSame(json_decode($expectedJson, true), $response);
    }
}
