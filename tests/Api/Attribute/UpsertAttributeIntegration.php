<?php

namespace Akeneo\Pim\tests\Api\Attribute;

use Akeneo\Pim\tests\Api\ApiTestCase;

class UpsertAttributeIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getAttributeApi();

        $response = $api->upsert('name', [
            'max_characters' => 42,
            'labels'         => [
                'fr_FR' => 'Nom',
            ],
        ]);

        $this->assertSame(204, $response);

        $attribute = $api->get('name');
        $this->assertSameContent([
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
            'max_characters'         => 42,
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
                'fr_FR' => 'Nom',
            ],
        ], $attribute);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getAttributeApi();
        $response = $api->upsert('comment', [
            'type'                   => 'pim_catalog_text',
            'group'                  => 'other',
            'unique'                 => false,
            'useable_as_grid_filter' => false,
            'max_characters'         => null,
            'validation_rule'        => null,
            'validation_regexp'      => null,
            'localizable'            => true,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'Comment',
            ],
        ]);

        $this->assertSame(201, $response);

        $attribute = $api->get('comment');
        $this->assertSameContent([
            'code'                   => 'comment',
            'type'                   => 'pim_catalog_text',
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
            'decimals_allowed'       => null,
            'negative_allowed'       => null,
            'date_min'               => null,
            'date_max'               => null,
            'max_file_size'          => null,
            'minimum_input_length'   => null,
            'sort_order'             => 0,
            'localizable'            => true,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'Comment',
            ],
        ], $attribute);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getAttributeApi();
        $api->upsert('name', [
            'max_characters' => false,
            'labels'         => [
                'fr_FR' => [],
            ],
        ]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getAttributeApi();
        $api->upsert('invalid code !', [
            'type'                   => 'pim_catalog_text',
            'group'                  => 'other',
            'unique'                 => false,
            'useable_as_grid_filter' => false,
            'max_characters'         => null,
            'validation_rule'        => null,
            'validation_regexp'      => null,
            'localizable'            => true,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'Comment',
            ],
        ]);
    }
}
