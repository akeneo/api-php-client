<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntity;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UpsertReferenceEntityIntegration extends ApiTestCase
{
    public function test_upsert_reference_entity()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(ReferenceEntityApi::REFERENCE_ENTITY_URI, 'brand'),
            new ResponseStack(
                new Response('', [], 204)
            )
        );

        $referenceEntity = [
            'code' => 'brand',
            'labels' => [
                'en_US' => 'Brand'
            ]
        ];

        $api = $this->createClientByPassword()->getReferenceEntityApi();
        $response = $api->upsert('brand', $referenceEntity);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($referenceEntity));
        Assert::assertSame(204, $response);
    }
}
