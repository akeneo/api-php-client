<?php

namespace Akeneo\Pim\tests\Api\Family;

use Akeneo\Pim\tests\Api\ApiTestCase;

class CreateFamilyApiIntegration extends ApiTestCase
{
    public function testCreate()
    {
        $api = $this->createClient()->getFamilyApi();
        $response = $api->create('running', [
            'attributes' => [
                'color',
                'description',
                'manufacturer',
                'name',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Running',
                'fr_FR' => 'Course',
            ],
        ]);

        $this->assertSame(201, $response);

        $family = $api->get('running');
        $this->assertSameContent([
            'code'   => 'running',
            'attributes' => [
                'color',
                'description',
                'manufacturer',
                'name',
                'sku',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'sku',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Running',
                'fr_FR' => 'Course',
            ],
        ], $family);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnExistingFamily()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->create('boots', [
            'attributes' => [
                'color',
                'description',
                'manufacturer',
                'name',
                'price',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'sku',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Boots',
                'fr_FR' => 'Bottes',
            ],
        ]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidFamily()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->create('fail', [
            'attributes' => [
                'sku',
                'unknown_attribute',
            ],
            'attribute_as_label'     => 'unknown_attribute',
            'attribute_requirements' => [
                'ecommerce' => [
                    'sku',
                ],
                'mobile' => [
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Fail',
                'fr_FR' => 'Fail',
            ],
        ]);
    }
}
