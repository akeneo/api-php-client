<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntityRecord;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class GetReferenceEntityRecordIntegration extends ApiTestCase
{
    public function test_get_reference_entity_record()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORD_URI, 'designer', 'starck'),
            new ResponseStack(
                new Response($this->getStarckRecord(), [], 200)
            )
        );

        $api = $this->createClientByPassword()->getReferenceEntityRecordApi();
        $product = $api->get('designer', 'starck');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertEquals($product, json_decode($this->getStarckRecord(), true));
    }

    public function test_get_unknow_product()
    {
        $this->expectExceptionMessage("Record \"foo\" does not exist for the reference entity \"designer\".");
        $this->expectException(\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException::class);
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORD_URI, 'designer', 'foo'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Record \"foo\" does not exist for the reference entity \"designer\"."}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getReferenceEntityRecordApi();
        $api->get('designer', 'foo');
    }

    private function getStarckRecord(): string
    {
        return <<<JSON
            {
              "code": "starck",
              "values": {
                "label": [
                  {
                    "locale": "en_US",
                    "channel": null,
                    "data": "Philippe Starck"
                  }
                ]
              }
            }
JSON;
    }
}
