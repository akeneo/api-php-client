<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

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

        $parameters = [
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
        ];
        $response = $api->upsert('docks_black', $parameters);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($parameters));

        Assert::assertSame(204, $response);
    }

}
