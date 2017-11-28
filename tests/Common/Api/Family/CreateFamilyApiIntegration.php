<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Family;

use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

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

    public function testCreateAnExistingFamily()
    {
        $api = $this->createClient()->getFamilyApi();

        try {
            $api->create('boots', [
                'attributes'             => [
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
                    'mobile'    => [
                        'name',
                        'sku',
                    ],
                ],
                'labels'                 => [
                    'en_US' => 'Boots',
                    'fr_FR' => 'Bottes',
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
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
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
