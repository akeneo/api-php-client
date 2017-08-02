<?php

namespace Akeneo\Pim\tests\v1_8\Api\AssociationType;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateAssociationTypeApiIntegration extends ApiTestCase
{
    public function testCreate()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $response = $api->create(
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

    public function testCreateAnExistingAssociationType()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        try {
            $api->create(
                'UPSELL',
                [
                    'labels' => [
                        'en_US' => 'Upsell',
                    ],
                ]
            );
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame(
                [
                    [
                        'property' => 'code',
                        'message'  => 'This value is already used.',
                    ],
                ],
                $exception->getResponseErrors()
            );
        }
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidAssociationType()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $api->create(
            'fail',
            [
                'labels' => 'Upsell',
            ]
        );
    }
}
