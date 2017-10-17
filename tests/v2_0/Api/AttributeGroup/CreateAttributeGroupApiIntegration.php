<?php

namespace Akeneo\Pim\tests\v2_0\Api\AttributeGroup;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateAttributeGroupApiIntegration extends ApiTestCase
{
    public function testCreate()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $response = $api->create(
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
        $this->assertSameContent(
            [
                'code'       => 'tech',
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'labels'     => [
                    'en_US' => 'Tech',
                ],
            ],
            $attributeGroup
        );
    }

    public function testCreateAnExistingAttributeGroup()
    {
        $api = $this->createClient()->getAttributeGroupApi();

        try {
            $api->create(
                'info',
                [
                    'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                    'sort_order' => 1,
                    'labels'     => [
                        'en_US' => 'Product information',
                    ],
                ]
            );
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame(
                [
                    [
                        'property' => 'code',
                        'message'  => 'This value is already used.',
                    ],
                ],
                $exception->getResponseErrors()
            );
        }
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidCategory()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $api->create(
            'fail',
            [
                'labels' => ['fail'],
            ]
        );
    }
}
