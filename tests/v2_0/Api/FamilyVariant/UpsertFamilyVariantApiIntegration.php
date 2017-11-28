<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\FamilyVariant;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class UpsertFamilyVariantApiIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getFamilyVariantApi();

        $response = $api->upsert('boots', 'rain_boots_color_size', [
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe.',
                'en_US' => 'Rain boots better use it during rain.',
                'fr_FR' => 'Bottes de pluie à utiliser pendant la pluie.'
            ]
        ]);

        $this->assertSame(204, $response);

        $familyVariant = $api->get('boots', 'rain_boots_color_size');

        $this->assertSameContent([
            'code' => 'rain_boots_color_size',
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe.',
                'en_US' => 'Rain boots better use it during rain.',
                'fr_FR' => 'Bottes de pluie à utiliser pendant la pluie.'
            ],
            'variant_attribute_sets' => [
                [
                    'level' => 1,
                    'axes' => ['color'],
                    'attributes' => [
                        'name',
                        'description',
                        'side_view',
                        'color'
                    ]
                ],
                [
                    'level' => 2,
                    'axes' => ['size'],
                    'attributes' => ['sku', 'size']
                ]
            ]
        ], $familyVariant);
    }

    public function testUpsertDoingCreate()
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
        $response = $api->upsert('boots', 'super_boots_color_size', $data);

        $this->assertSame(201, $response);
        sleep(10);
        $familyVariant = $api->get('boots', 'super_boots_color_size');

        $data['code'] = 'super_boots_color_size';
        $this->assertSameContent($data, $familyVariant);
    }
}
