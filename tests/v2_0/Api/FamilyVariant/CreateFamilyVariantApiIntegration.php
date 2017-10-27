<?php

namespace Akeneo\Pim\tests\v2_0\Api\FamilyVariant;

use Akeneo\Pim\Exception\InvalidArgumentException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateFamilyVariantApiIntegration extends ApiTestCase
{
    public function testCreateAFamilyVariant()
    {
        $api = $this->createClient()->getFamilyVariantApi();
        $data = [
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe',
                'en_US' => 'Boots by color and size',
                'fr_FR' => 'Bottes par couleur et taille'
            ],
            'variant_attribute_sets' => [
                [
                    'level' => 1,
                    'axes' => ['size'],
                    'attributes' => [
                        'name',
                        'description',
                        'size'
                    ]
                ],
                [
                    'level' => 2,
                    'axes' => ['color'],
                    'attributes' => ['sku', 'color']
                ]
            ]
        ];
        $response = $api->create('boots', 'super_boots_color_size', $data);
        $this->assertSame(201, $response);

        $familyVariant = $api->get('boots', 'super_boots_color_size');

        $data['code'] = 'super_boots_color_size';
        $this->assertSameContent($data, $familyVariant);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     * @expectedExceptionMessage Validation failed
     */
    public function testFailedToCreateAFamilyVariant()
    {
        $api = $this->createClient()->getFamilyVariantApi();
        $data = [
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe',
                'en_US' => 'Boots by color and size',
                'fr_FR' => 'Bottes par couleur et taille'
            ],
            'variant_attribute_sets' => [
                [
                    'level' => 2,
                    'axes' => ['color'],
                    'attributes' => ['sku', 'color']
                ]
            ]
        ];
        $response = $api->create('boots', 'boots_size_color', $data);
        $this->assertSame(422, $response);
    }
}
