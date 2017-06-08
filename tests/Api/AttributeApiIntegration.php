<?php

namespace Akeneo\Pim\tests\Api;

class AttributeApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getAttributeApi();

        $attribute = $api->get('camera_brand');

        $this->assertInternalType('array', $attribute);

        $this->assertSameResponseBody(['pouet' => true], $attribute);
    }
}
