<?php

namespace Akeneo\Pim\tests\Common\Api\Attribute;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetAttributeIntegration extends ApiTestCase
{
    /**
     * @group common
     */
    public function testGet()
    {
        $api = $this->createClient()->getAttributeApi();

        $attribute = $api->get('length');

        $this->assertSameContent([
            'code'                   => 'length',
            'type'                   => 'pim_catalog_metric',
            'group'                  => 'info',
            'unique'                 => false,
            'useable_as_grid_filter' => false,
            'allowed_extensions'     => [],
            'metric_family'          => 'Length',
            'default_metric_unit'    => 'CENTIMETER',
            'reference_data_name'    => null,
            'available_locales'      => [],
            'max_characters'         => null,
            'validation_rule'        => null,
            'validation_regexp'      => null,
            'wysiwyg_enabled'        => null,
            'number_min'             => null,
            'number_max'             => null,
            'decimals_allowed'       => false,
            'negative_allowed'       => false,
            'date_min'               => null,
            'date_max'               => null,
            'max_file_size'          => null,
            'minimum_input_length'   => null,
            'sort_order'             => 10,
            'localizable'            => false,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'Length',
                'fr_FR' => 'Longueur',
            ],
        ], $attribute);
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getAttributeApi();

        $api->get('unknown');
    }
}
