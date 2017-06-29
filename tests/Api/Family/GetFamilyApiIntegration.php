<?php

namespace Akeneo\Pim\tests\Api\Family;

use Akeneo\Pim\tests\Api\ApiTestCase;

class GetFamilyApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getFamilyApi();
        $family = $api->get('boots');

        $expectedFamily = [
            'code'       => 'boots',
            'attributes' => [
                'color',
                'description',
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
        ];

        $this->assertSameContent($expectedFamily, $family);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getFamilyApi();
        $api->get('Addams');
    }
}
