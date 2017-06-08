<?php

namespace Akeneo\Pim\tests\integration\Api;

class FamilyApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->pimClient->getFamilyApi();

        $family = $api->get('mugs');

        $this->assertInternalType('array', $family);
    }
}
