<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Family;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class GetFamilyApiIntegration extends ApiTestCase
{
    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->get('Addams');
    }
}
