<?php

namespace Akeneo\Pim\tests\Common\Api\Attribute;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateAttributeIntegration extends ApiTestCase
{
    public function testCreate()
    {
        $api = $this->createClient()->getAttributeApi();
        $response = $api->create('comment', [
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

    public function testCreateAnExistingAttribute()
    {
        $api = $this->createClient()->getAttributeApi();

        try {
            $api->create('name', [
                'type'                   => 'pim_catalog_text',
                'group'                  => 'info',
                'unique'                 => false,
                'useable_as_grid_filter' => true,
                'metric_family'          => null,
                'default_metric_unit'    => null,
                'reference_data_name'    => null,
                'max_characters'         => null,
                'validation_rule'        => null,
                'validation_regexp'      => null,
                'sort_order'             => 2,
                'localizable'            => true,
                'scopable'               => false,
                'labels'                 => [
                    'en_US' => 'Name',
                ],
            ]);
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame([
                [
                    'property' => 'code',
                    'message'  => 'This value is already used.',
                ],
            ], $exception->getResponseErrors());
        }
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidAttribute()
    {
        $api = $this->createClient()->getAttributeApi();
        $api->create('fail', [
            'type'                   => 'unknown_type',
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
