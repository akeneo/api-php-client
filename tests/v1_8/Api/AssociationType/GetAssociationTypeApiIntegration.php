<?php

namespace Akeneo\Pim\tests\v1_8\Api\AssociationType;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetAssociationTypeApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $associationType = $api->get('X_SELL');

        $this->assertSameContent([
            'code'             => 'X_SELL',
            'labels'           => [
                'en_US' => 'Cross sell',
            ],
        ], $associationType);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $api->get('unknown');
    }
}
