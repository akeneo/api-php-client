<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Family;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class UpsertFamilyApiIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getFamilyApi();

        $response = $api->upsert('boots', [
            'attributes' => [
                'color',
                'description',
                'manufacturer',
                'name',
                'price',
                'side_view',
                'size',
                'sku',
                'heel_color',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'price',
                    'sku',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Boots!',
                'fr_FR' => 'Bottes',
            ],
        ]);

        $this->assertSame(204, $response);

        $family = $api->get('boots');
        $this->assertSameContent([
            'attributes' => [
                'color',
                'description',
                'heel_color',
                'manufacturer',
                'name',
                'price',
                'side_view',
                'size',
                'sku',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'price',
                    'sku',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Boots!',
                'fr_FR' => 'Bottes',
            ],
        ], $family);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getFamilyApi();
        $response = $api->upsert('running', [
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
            'code'       => 'running',
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
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->upsert('boots', [
            'attributes' => 'colors',
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'price',
                    'side_view',
                    'size',
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
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->upsert('invalid code !', [
            'attributes' => [
                'color',
                'description',
                'manufacturer',
                'name',
                'price',
                'side_view',
                'size',
                'sku',
                'heel_color',
            ],
            'attribute_as_label'     => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'price',
                    'sku',
                ],
                'mobile' => [
                    'name',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Boots!',
                'fr_FR' => 'Bottes',
            ],
        ]);
    }
}
