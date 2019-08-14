<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetProductTest extends ApiTestCase
{
    public function test_get_product()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(ProductApi::PRODUCT_URI, 'black_sneakers'),
            new ResponseStack(
                new Response($this->getProduct(), [], 200)
            )
        );

        $api = $this->createClient()->getProductApi();

        $product = $api->get('black_sneakers');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($product, json_decode($this->getProduct(), true));
    }

    public function test_get_unknow_product()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(ProductApi::PRODUCT_URI, 'black_sneakers'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Resource `black_sneakers` does not exist."}', [], 404)
            )
        );

        $this->expectException(\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource `black_sneakers` does not exist.');

        $api = $this->createClient()->getProductApi();
        $api->get('black_sneakers');
    }

    private function getProduct()
    {
        return <<<JSON
            [
                {
                    "identifier" : "black_sneakers",
                    "family" : "sneakers",
                    "groups": [],
                    "categories": ["summer_collection"],
                    "values": [{
                        "color": {"locale": null, "scope": null, "data": "black"}
                    }]
                }
            ]
JSON;
    }
}
