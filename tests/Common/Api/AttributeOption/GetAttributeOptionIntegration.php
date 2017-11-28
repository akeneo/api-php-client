<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\AttributeOption;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class GetAttributeOptionIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getAttributeOptionApi();

        $attributeOption = $api->get('weather_conditions', 'hot');

        $this->assertSameContent([
            'code'       => 'hot',
            'attribute'  => 'weather_conditions',
            'sort_order' => 3,
            'labels'     => [
                'en_US' => 'Hot',
            ]
        ], $attributeOption);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getAttributeOptionApi();

        $api->get('weather_conditions', 'unknown');
    }
}
