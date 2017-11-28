<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\AssociationType;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class UpsertAssociationTypeApiIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $response = $api->upsert(
            'X_SELL',
            [
                'labels' => [
                    'en_US' => 'Cross sell',
                    'fr_FR' => 'Vente croisée',
                ],
            ]
        );

        $this->assertSame(204, $response);

        $associationType = $api->get('X_SELL');
        $this->assertSameContent(
            [
                'code'   => 'X_SELL',
                'labels' => [
                    'en_US' => 'Cross sell',
                    'fr_FR' => 'Vente croisée',
                ],
            ],
            $associationType
        );
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $response = $api->upsert(
            'NEW_SELL',
            [
                'labels' => [
                    'en_US' => 'New sell',
                    'fr_FR' => 'Nouvelle vente',
                ],
            ]
        );

        $this->assertSame(201, $response);

        $associationType = $api->get('NEW_SELL');
        $this->assertSameContent(
            [
                'code'   => 'NEW_SELL',
                'labels' => [
                    'en_US' => 'New sell',
                    'fr_FR' => 'Nouvelle vente',
                ],
            ],
            $associationType
        );
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $api->upsert(
            'NEW_SELL',
            [
                'labels' => [
                    'en_US' => ['New sell'],
                ],
            ]
        );
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getCategoryApi();
        $api->upsert('invalid code !');
    }
}
