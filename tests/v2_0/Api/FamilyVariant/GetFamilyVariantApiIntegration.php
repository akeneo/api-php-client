<?php

namespace Akeneo\Pim\tests\v2_0\Api\FamilyVariant;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetFamilyVariantApiIntegration extends ApiTestCase
{
    public function testGetFamilyVariant()
    {
        $api = $this->createClient()->getFamilyVariantApi();

        $familyVariant = $api->get('boots', 'rain_boots_color_size');

        $expected = [
            'code' => 'rain_boots_color_size',
            'labels' => [
                'de_DE' => 'Stiefel nach Farbe und Größe',
                'en_US' => 'Rain boots by color and size',
                'fr_FR' => 'Bottes de pluie par couleur et taille'
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
        ];

        $this->assertSameContent($expected, $familyVariant);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     * @expectedExceptionMessage Family variant "trololo" does not exist or is not a variant of the family "boots".
     */
    public function testFamilyVariantNotFound()
    {
        $this->createClient()->getFamilyVariantApi()->get('boots', 'trololo');
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     * @expectedExceptionMessage Family "trololo" does not exist.
     */
    public function testFamilyNotFound()
    {
        $this->createClient()->getFamilyVariantApi()->get('trololo', 'rain_boots_color_size');
    }
}
