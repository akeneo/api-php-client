<?php

namespace Akeneo\Pim\tests\integration\Api;

class AttributeApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->pimClient->getAttributeApi();

        $attribute = $api->get('camera_brand');

        $this->assertInternalType('array', $attribute);

        $this->assertSameResponseBody(['pouet' => true], $attribute);
    }
}
