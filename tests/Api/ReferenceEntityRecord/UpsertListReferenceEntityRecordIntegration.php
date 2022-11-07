<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntityRecord;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertListReferenceEntityRecordIntegration extends ApiTestCase
{
    public function test_upsert_a_list_of_reference_entity_records()
    {
        $responseBody = <<<JSON
        [
          {
            "code": "starck",
            "status_code": 204
          },
          {
            "code": "dyson",
            "status_code": 201
          }
        ]
JSON;

        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORDS_URI, 'designer'),
            new ResponseStack(
                new Response($responseBody, [], 200)
            )
        );

        $records = [
            [
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
            ],
            [
                'code' => 'dyson',
                'values' => [
                    'label' => [
                        [
                            'channel' => null,
                            'locale' => 'en_US',
                            'data' => 'James Dyson'
                        ],
                    ]
                ]
            ]
        ];

        $expectedResponses = [
            [
                'code' => 'starck',
                'status_code' => 204
            ],
            [
                'code' => 'dyson',
                'status_code' => 201
            ],
        ];

        $api = $this->createClientByPassword()->getReferenceEntityRecordApi();
        $responses = $api->upsertList('designer', $records);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($records));
        Assert::assertSame($expectedResponses, $responses);
    }
}
