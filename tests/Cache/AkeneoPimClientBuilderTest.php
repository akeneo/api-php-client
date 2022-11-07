<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Cache;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class AkeneoPimClientBuilderTest extends ApiTestCase
{
    public function test_get_product_with_create_client_by_password()
    {
        $this->setGetProductResponse();
        $api = $this->createClientByPassword()->getProductApi();

        $product = $api->get('black_sneakers');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getProduct(), true));
    }

    public function test_get_product_with_create_client_by_token()
    {
        $this->setGetProductResponse();
        $api = $this->createClientByToken()->getProductApi();

        $product = $api->get('black_sneakers');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getProduct(), true));
    }

    public function test_get_product_with_create_client_by_app_token()
    {
        $this->setGetProductResponse();
        $api = $this->createClientByAppToken()->getProductApi();

        $product = $api->get('black_sneakers');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getProduct(), true));
    }

    private function setGetProductResponse(): void
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ProductApi::PRODUCT_URI, 'black_sneakers'),
            new ResponseStack(
                new Response($this->getProduct(), [], 200)
            )
        );
    }

    private function getProduct(): string
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
