<?php

namespace Akeneo\Pim\tests\Common\Api\Family;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetFamilyApiIntegration extends ApiTestCase
{
    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->get('Addams');
    }
}
