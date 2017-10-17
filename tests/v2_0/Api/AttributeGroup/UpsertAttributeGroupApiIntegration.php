<?php

namespace Akeneo\Pim\tests\v2_0\Api\AttributeGroup;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class UpsertAttributeGroupApiIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        $response = $api->upsert(
            'info',
            [
                'attributes' => ['weather_conditions'],
                'sort_order' => 1,
                'labels'     => [
                    'en_US' => 'Product information',
                ],
            ]
        );

        $this->assertSame(204, $response);

        $attributeGroup = $api->get('info');
        $this->assertSameContent([
            'code'   => 'info',
            'attributes' => ['weather_conditions'],
            'sort_order' => 1,
            'labels'     => [
                'en_US' => 'Product information',
            ],
        ], $attributeGroup);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $response = $api->upsert(
            'tech',
            [
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'labels'     => [
                    'en_US' => 'Tech',
                ],
            ]
        );

        $this->assertSame(201, $response);

        $attributeGroup = $api->get('tech');
        $this->assertSameContent([
            'code' => 'tech',
            'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
            'labels'     => [
                'en_US' => 'Tech',
            ],
        ], $attributeGroup);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $api->upsert('tech', [
            'labels' => [
                'en_US' => ['wrong data type'],
                'fr_FR' => 'Sandales',
            ],
        ]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $api->upsert('invalid code !', [
            'labels' => [
                'en_US' => 'Invalid code',
                'fr_FR' => 'Code invalide',
            ],
        ]);
    }
}
