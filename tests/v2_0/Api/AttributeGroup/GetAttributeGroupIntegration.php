<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\AttributeGroup;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class GetAttributeGroupIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        $attributeGroup = $api->get('info');

        $this->assertSameContent([
            'code'       => 'info',
            'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
            'sort_order' => 1,
            'labels'     => [
                'en_US' => 'Product information',
            ],
        ], $attributeGroup);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        $api->get('unknown');
    }
}
