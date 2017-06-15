<?php

namespace Akeneo\Pim\tests\Api;

class FamilyApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getFamilyApi();
        $family = $api->get('boots');
        $this->assertInternalType('array', $family);
        $this->assertSame([
            'code' => 'boots',
            'attributes' => [
                'color',
                'description',
                'lace_color',
                'manufacturer',
                'name',
                'price',
                'rating',
                'side_view',
                'size',
                'sku',
                'top_view',
                'weather_conditions',
            ],
            'attribute_as_label' => 'name',
            'attribute_requirements' => [
                'ecommerce' => [
                    'color',
                    'description',
                    'name',
                    'price',
                    'rating',
                    'side_view',
                    'size',
                    'sku',
                    'weather_conditions',
                ],
                'mobile' => [
                    'color',
                    'name',
                    'price',
                    'size',
                    'sku',
                ],
            ],
            'labels' => [
                'en_US' => 'Boots',
                'fr_FR' => 'Bottes',
            ]
        ], $family);
    }
}
