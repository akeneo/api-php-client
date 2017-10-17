<?php

namespace Akeneo\Pim\tests\v2_0\Api\AssociationType;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertListAssociationTypeApiIntegration extends ApiTestCase
{
    public function testUpsertListFromArraySuccessful()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $response = $api->upsertList([
            [
                'code'             => 'X_SELL',
                'labels'           => [
                    'en_US' => 'Cross sell',
                    'fr_FR' => 'Vente croisée',
                ],
            ],
            [
                'code'   => 'NEW_SELL',
                'labels' => [
                    'en_US' => 'New sell',
                    'fr_FR' => 'Nouvelle vente',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'X_SELL',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'NEW_SELL',
                'status_code' => 201,
            ]
        ], $responseLines);
    }

    public function testUpsertListFromStreamSuccessful()
    {
        $resourcesContent =
            <<<JSON
{"code":"X_SELL","labels":{"en_US":"Cross sell","fr_FR":"Vente croisée"}}
{"code":"NEW_SELL","labels":{"en_US":"New sell","fr_FR":"Nouvelle vente"}}
JSON;
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $resourcesContent);
        rewind($resources);

        $streamedResources = $this->getStreamFactory()->createStream($resources);
        $api = $this->createClient()->getAssociationTypeApi();
        $response = $api->upsertList($streamedResources);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'code'        => 'X_SELL',
                'status_code' => 204,
            ],
            2 => [
                'line'        => 2,
                'code'        => 'NEW_SELL',
                'status_code' => 201,
            ]
        ], $responseLines);
    }

    public function testUpsertListFailed()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $response = $api->upsertList([
            [
                'labels' => [
                    'en_US' => 'Cross sell',
                    'fr_FR' => 'Vente croisée',
                ],
            ],
            [
                'code'   => 'NEW_SELL',
                'labels' => [
                    'en_US' => 'line too long' . str_repeat('a', 1000000),
                    'fr_FR' => 'Nouvelle vente',
                ],
            ]
        ]);

        $this->assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);

        $this->assertSame([
            1 => [
                'line'        => 1,
                'status_code' => 422,
                'message'     => 'Code is missing.',
            ],
            2 => [
                'line'        => 2,
                'status_code' => 413,
                'message'     => 'Line is too long.',
            ]
        ], $responseLines);
    }
}
