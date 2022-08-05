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
 * @todo After testing
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class CreateAppCatalogIntegration extends ApiTestCase
{
    public function test_upsert_catalog()
    {
        $catalogData = ['name' => 'A catalog name'];

        $this->server->setResponseOfPath(
            '/'.AppCatalogApi::APP_CATALOGS_URI,
            new ResponseStack(
                new Response('', [
                    'id' => '12351d98-200e-4bbc-aa19-7fdda1bd14f2',
                    'name' => 'A catalog name',
                    'enabled' => false,
                ], HttpClient::HTTP_CREATED)
            )
        );

        $api = $this->createClientByPassword()->getAppCatalogApi();
        $response = $api->create($catalogData);

        // Assert last request code est bien un created

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            json_encode($catalogData)
        );
        Assert::assertSame(HttpClient::HTTP_OK, $response);
    }
}
