<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Attribute;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class ListAttributeIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getAttributeApi();
        $expectedAttributes = $this->getExpectedAttributes();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(7);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=2&limit=7&with_count=false', $firstPage->getNextLink());

        $attributes = $firstPage->getItems();
        $this->assertCount(7 ,$attributes);
        for ($i = 0; $i < 7; $i++) {
            $this->assertSameContent($expectedAttributes[$i], $attributes[$i]);
        }

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=1&limit=7&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=3&limit=7&with_count=false', $secondPage->getNextLink());

        $attributes = $secondPage->getItems();
        $this->assertCount(7 ,$attributes);
        for ($i = 0; $i < 7; $i++) {
            $this->assertSameContent($expectedAttributes[7 + $i], $attributes[$i]);
        }

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=2&limit=7&with_count=false', $lastPage->getPreviousLink());

        $attributes = $lastPage->getItems();
        $this->assertCount(1 ,$attributes);
        $this->assertSameContent($expectedAttributes[14], $attributes[0]);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSameContent($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getAttributeApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(5, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(15, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=2&limit=5&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getAttributeApi();
        $expectedAttributes = $this->getExpectedAttributes();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/attributes?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $attributes = $firstPage->getItems();
        $this->assertCount(2 ,$attributes);
        $this->assertSameContent($expectedAttributes[0], $attributes[0]);
        $this->assertSameContent($expectedAttributes[1], $attributes[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getAttributeApi();
        $attributes = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributes);

        $attributes = iterator_to_array($attributes);

        $this->assertCount(15, $attributes);
        $this->assertSameContent($this->getExpectedAttributes(), $attributes);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getAttributeApi();
        $attributes = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributes);

        $attributes = iterator_to_array($attributes);

        $this->assertCount(15, $attributes);
        $this->assertSameContent($this->getExpectedAttributes(), $attributes);
    }

    /**
     * @return array
     */
    protected function getExpectedAttributes()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/color',
                    ],
                ],
                'code'                   => 'color',
                'type'                   => 'pim_catalog_simpleselect',
                'group'                  => 'colors',
                'unique'                 => false,
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
                'sort_order'             => 9,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Color',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/description',
                    ],
                ],
                'code'                   => 'description',
                'type'                   => 'pim_catalog_textarea',
                'group'                  => 'info',
                'unique'                 => false,
                'useable_as_grid_filter' => true,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'available_locales'      => [],
                'max_characters'         => 1000,
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
                'sort_order'             => 5,
                'localizable'            => true,
                'scopable'               => true,
                'labels'                 => [
                    'en_US' => 'Description',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/destocking_date',
                    ],
                ],
                'code'                   => 'destocking_date',
                'type'                   => 'pim_catalog_date',
                'group'                  => 'other',
                'unique'                 => false,
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
                'sort_order'             => 12,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Destocking date',
                    'fr_FR' => 'Date de déstockage',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/handmade',
                    ],
                ],
                'code'                   => 'handmade',
                'type'                   => 'pim_catalog_boolean',
                'group'                  => 'other',
                'unique'                 => false,
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
                'sort_order'             => 13,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Handmade',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/heel_color',
                    ],
                ],
                'code'                   => 'heel_color',
                'type'                   => 'pim_reference_data_simpleselect',
                'group'                  => 'colors',
                'unique'                 => false,
                'useable_as_grid_filter' => true,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => 'color',
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
                'sort_order'             => 14,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Heel color',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/length',
                    ],
                ],
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
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/manufacturer',
                    ],
                ],
                'code'                   => 'manufacturer',
                'type'                   => 'pim_catalog_simpleselect',
                'group'                  => 'info',
                'unique'                 => false,
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
                'sort_order'             => 3,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Manufacturer',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/name',
                    ],
                ],
                'code'                   => 'name',
                'type'                   => 'pim_catalog_text',
                'group'                  => 'info',
                'unique'                 => false,
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
                'sort_order'             => 2,
                'localizable'            => true,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Name',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/number_in_stock',
                    ],
                ],
                'code'                   => 'number_in_stock',
                'type'                   => 'pim_catalog_number',
                'group'                  => 'other',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
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
                'decimals_allowed'       => false,
                'negative_allowed'       => false,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => null,
                'sort_order'             => 11,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Number in stock',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/price',
                    ],
                ],
                'code'                   => 'price',
                'type'                   => 'pim_catalog_price_collection',
                'group'                  => 'marketing',
                'unique'                 => false,
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
                'number_min'             => '1.0000',
                'number_max'             => '200.0000',
                'decimals_allowed'       => true,
                'negative_allowed'       => null,
                'date_min'               => null,
                'date_max'               => null,
                'max_file_size'          => null,
                'minimum_input_length'   => null,
                'sort_order'             => 6,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Price',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/side_view',
                    ],
                ],
                'code'                   => 'side_view',
                'type'                   => 'pim_catalog_image',
                'group'                  => 'media',
                'unique'                 => false,
                'useable_as_grid_filter' => false,
                'allowed_extensions'     => [
                    'gif',
                    'png',
                    'jpeg',
                    'jpg',
                ],
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
                'max_file_size'          => '1.00',
                'minimum_input_length'   => null,
                'sort_order'             => 7,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Side view',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/size',
                    ],
                ],
                'code'                   => 'size',
                'type'                   => 'pim_catalog_simpleselect',
                'group'                  => 'sizes',
                'unique'                 => false,
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
                'sort_order'             => 8,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Size',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/sku',
                    ],
                ],
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
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/sole_color',
                    ],
                ],
                'code'                   => 'sole_color',
                'type'                   => 'pim_reference_data_simpleselect',
                'group'                  => 'colors',
                'unique'                 => false,
                'useable_as_grid_filter' => true,
                'allowed_extensions'     => [],
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => 'color',
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
                'sort_order'             => 15,
                'localizable'            => false,
                'scopable'               => true,
                'labels'                 => [
                    'en_US' => 'Sole color',
                ],
            ],
            [
                '_links'                 => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions',
                    ],
                ],
                'code'                   => 'weather_conditions',
                'type'                   => 'pim_catalog_multiselect',
                'group'                  => 'info',
                'unique'                 => false,
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
                'sort_order'             => 4,
                'localizable'            => false,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Weather conditions',
                ],
            ],

        ];
    }
}
