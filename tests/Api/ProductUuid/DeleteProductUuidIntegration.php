<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ProductUuid;

use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
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
class DeleteProductUuidIntegration extends ApiTestCase
{
    public function test_create_product()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ProductUuidApi::PRODUCT_UUID_URI, '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'),
            new ResponseStack(
                new Response('', [], HttpClient::HTTP_NO_CONTENT)
            )
        );

        $api = $this->createClientByPassword()->getProductUuidApi();

        $response = $api->delete('12951d98-210e-4bRC-ab18-7fdgf1bd14f3');

        Assert::assertSame('DELETE', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertSame(HttpClient::HTTP_NO_CONTENT, $response);
    }
}
