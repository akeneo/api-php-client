<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductModelApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class DeleteProductModelTest extends ApiTestCase
{
    public function test_delete_product_model()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ProductModelApi::PRODUCT_MODEL_URI, 'docks_white'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $api = $this->createClientByPassword()->getProductModelApi();

        $response = $api->delete('docks_white');

        Assert::assertSame('DELETE', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertSame(204, $response);
    }
}
