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
class GetAppCatalogIntegration extends ApiTestCase
{
    private string $aCatalogId = '12351d98-200e-4bbc-aa19-7fdda1bd14f2';

    public function test_get_asset()
    {
        $this->server->setResponseOfPath(
            '/'.sprintf(AppCatalogApi::APP_CATALOG_URI, $this->aCatalogId),
            new ResponseStack(
                new Response($this->getACatalog(), [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $asset = $api->get($this->aCatalogId);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($asset, json_decode($this->getACatalog(), true));
    }

    private function getACatalog(): string
    {
        return <<<JSON
{
    "id": "$this->aCatalogId",
     "name": "A catalog name",
     "enabled": false
}
JSON;
    }
}
