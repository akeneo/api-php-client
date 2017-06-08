<?php

namespace Akeneo\Pim\tests\Api;

class FamilyApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getFamilyApi();

        $family = $api->get('mugs');

        $this->assertInternalType('array', $family);
    }
}
