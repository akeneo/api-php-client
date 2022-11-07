<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntityRecord;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertReferenceEntityRecordIntegration extends ApiTestCase
{
    public function test_upsert_reference_entity_record()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORD_URI, 'designer', 'starck'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $recordData = [
            'code' => 'starck',
            'values' => [
                'label' => [
                    [
                        'channel' => null,
                        'locale' => 'en_US',
                        'data' => 'Philippe Starck'
                    ],
                ]
            ]
        ];

        $api = $this->createClientByPassword()->getReferenceEntityRecordApi();
        $response = $api->upsert('designer', 'starck', $recordData);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($recordData));
        Assert::assertSame(204, $response);
    }
}
