<?php

namespace Akeneo\Pim\tests\Common\Api\AttributeOption;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateAttributeOptionIntegration extends ApiTestCase
{
    /**
     * @group common
     */
    public function testCreate()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $response = $api->create('color', 'yellow', [
            'sort_order' => 9,
            'labels'     => [
                'en_US' => 'Yellow',
            ],
        ]);

        $this->assertSame(201, $response);

        $attributeOption = $api->get('color', 'yellow');
        $this->assertSameContent([
            'code'       => 'yellow',
            'attribute'  => 'color',
            'sort_order' => 9,
            'labels'     => [
                'en_US' => 'Yellow',
            ],
        ], $attributeOption);
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testCreateOnAnUnknownAttribute()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $api->create('foo', 'bar', [
            'sort_order' => 42,
            'labels'     => [
                'en_US' => 'FooBar',
            ],
        ]);
    }

    /**
     * @group common
     */
    public function testCreateAnExistingAttributeOption()
    {
        $api = $this->createClient()->getAttributeOptionApi();

        try {
            $api->create('color', 'black', [
                'sort_order' => 2,
                'labels'     => [
                    'en_US' => 'Black',
                ],
            ]);
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame([
                [
                    'property' => 'code',
                    'message'  => 'This value is already used.',
                ],
            ], $exception->getResponseErrors());
        }
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidAttributeOption()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $api->create('color', 'yellow', [
            'sort_order' => 9,
            'labels'     => [
                'en_US' => ['invalid type'],
            ],
        ]);
    }
}
