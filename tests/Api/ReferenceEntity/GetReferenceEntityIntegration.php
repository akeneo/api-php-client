<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntity;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetReferenceEntityIntegration extends ApiTestCase
{
    public function test_get_reference_entity()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityApi::REFERENCE_ENTITY_URI, 'brand'),
            new ResponseStack(
                new Response($this->getBrand(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getReferenceEntityApi();
        $product = $api->get('brand');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getBrand(), true));
    }

    public function test_get_unknown_reference_entity()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityApi::REFERENCE_ENTITY_URI, 'foo'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Reference entity \"foo\" does not exist."}', [], 404)
            )
        );

        $this->expectException(\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException::class);
        $this->expectExceptionMessage('Reference entity "foo" does not exist.');

        $api = $this->createClientByPassword()->getReferenceEntityApi();
        $api->get('foo');
    }

    private function getBrand(): string
    {
        return <<<JSON
            {
              "_links": {
                "image_download": {
                  "href": "https://demo.akeneo.com/api/rest/v1/reference-entities-media-files/0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
                }
              },
              "code": "brand",
              "labels": {
                "en_US": "Brand"
              },
              "image": "0/2/d/6/54d81dc888ba1501a8g765f3ab5797569f3bv756c_ref_img.png"
            }
JSON;
    }
}
