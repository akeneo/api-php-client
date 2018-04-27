<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;

class DeleteProductTest extends ApiTestCase
{
    public function test_create_product()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(ProductApi::PRODUCT_URI, 'docks_white'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $api = $this->createClient()->getProductApi();

        $response = $api->delete('docks_white');

        $this->assertSame(204, $response);
    }
}
