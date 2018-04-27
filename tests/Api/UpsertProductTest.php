<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;

class UpsertProductTest extends ApiTestCase
{
    public function test_upsert_product()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(ProductApi::PRODUCT_URI, 'docks_black'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $api = $this->createClient()->getProductApi();

        $response = $api->upsert('docks_black', [
            'enabled' => false,
            'values'  => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'Black Docks',
                    ],
                ],
            ]
        ]);
        $this->assertSame(204, $response);
    }

}
