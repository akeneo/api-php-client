<?php

namespace Akeneo\Pim\tests\Api;

class AttributeApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getAttributeApi();
        $attribute = $api->get('sku');
        $this->assertInternalType('array', $attribute);
        $this->assertSame([
            'code'                   => 'sku',
            'type'                   => 'pim_catalog_identifier',
            'group'                  => 'info',
            'unique'                 => true,
            'useable_as_grid_filter' => true,
            'allowed_extensions'     => [],
            'metric_family'          => null,
            'default_metric_unit'    => null,
            'reference_data_name'    => null,
            'available_locales'      => [],
            'max_characters'         => null,
            'validation_rule'        => null,
            'validation_regexp'      => null,
            'wysiwyg_enabled'        => null,
            'number_min'             => null,
            'number_max'             => null,
            'decimals_allowed'       => null,
            'negative_allowed'       => null,
            'date_min'               => null,
            'date_max'               => null,
            'max_file_size'          => null,
            'minimum_input_length'   => null,
            'sort_order'             => 1,
            'localizable'            => false,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'SKU',
            ],
        ], $attribute);
    }
}
