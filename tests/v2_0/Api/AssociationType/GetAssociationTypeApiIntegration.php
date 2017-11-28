<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\AssociationType;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

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
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getAssociationTypeApi();

        $api->get('unknown');
    }
}
