<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ProductDraftUuid;

use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApi;
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
class GetProductDraftUuidIntegration extends ApiTestCase
{
    public function test_get_product()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ProductDraftUuidApi::PRODUCT_DRAFT_UUID_URI, '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'),
            new ResponseStack(
                new Response($this->getProductDraft(), [], HttpClient::HTTP_OK)
            )
        );

        $api = $this->createClientByPassword()->getProductDraftUuidApi();
        $product = $api->get('12951d98-210e-4bRC-ab18-7fdgf1bd14f3');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getProductDraft(), true));
    }

    public function test_get_unknown_product()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ProductDraftUuidApi::PRODUCT_DRAFT_UUID_URI, '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Resource `12951d98-210e-4bRC-ab18-7fdgf1bd14f3` does not exist."}', [], 404)
            )
        );

        $this->expectException(\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource `12951d98-210e-4bRC-ab18-7fdgf1bd14f3` does not exist.');

        $api = $this->createClientByPassword()->getProductDraftUuidApi();
        $api->get('12951d98-210e-4bRC-ab18-7fdgf1bd14f3');
    }

    private function getProductDraft(): string
    {
        return <<<JSON
            [
                {
                    "uuid" : "12951d98-210e-4bRC-ab18-7fdgf1bd14f3",
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
