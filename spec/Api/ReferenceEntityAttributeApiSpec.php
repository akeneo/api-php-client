<?php

declare(strict_types=1);

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class ReferenceEntityAttributeApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_a_reference_entity_attribute_api()
    {
        $this->shouldImplement(ReferenceEntityAttributeApiInterface::class);
    }

    function it_returns_a_reference_entity_attribute(ResourceClientInterface $resourceClient)
    {
        $attribute = [
            'code'                         => 'description',
            'labels'                       => [
                'en_US' => 'Description',
                'fr_FR' => 'Description',
            ],
            'type'                         => 'text',
            'localizable'                  => true,
            'scopable'                     => false,
            'is_required_for_completeness' => true,
            'max_characters'               => null,
            'is_textarea'                  => true,
            'is_rich_text_editor'          => true,
            'validation_rule'              => null,
            'validation_regexp'            => null,
        ];

        $resourceClient
            ->getResource(ReferenceEntityAttributeApi::REFERENCE_ENTITY_ATTRIBUTE_URI, ['designer', 'description'])
            ->willReturn($attribute);

        $this->get('designer', 'description')->shouldReturn($attribute);
    }

    function it_returns_a_cursor_to_list_all_the_attributes_of_a_reference_entity(ResourceClientInterface $resourceClient)
    {
        $attributes = [
            [
                'code'                         => 'label',
                'labels'                       => [
                    'en_US' => 'Label',
                ],
                'type'                         => 'text',
                'localizable'                  => true,
                'scopable'                     => false,
                'is_required_for_completeness' => false,
                'max_characters'               => null,
                'is_textarea'                  => false,
                'is_rich_text_editor'          => false,
                'validation_rule'              => 'none',
                'validation_regexp'            => null,
                '_links'                       => [
                    'self' => [
                        'href' => 'http://localhost/api/rest/v1/reference-entities/designer/attributes/label',
                    ],
                ],
            ],
            [
                'code'                         => 'birthdate',
                'labels'                       => [
                    'en_US' => 'Birthdate',
                ],
                'type'                         => 'text',
                'localizable'                  => false,
                'scopable'                     => false,
                'is_required_for_completeness' => false,
                'max_characters'               => null,
                'is_textarea'                  => false,
                'is_rich_text_editor'          => false,
                'validation_rule'              => 'none',
                'validation_regexp'            => null,
                '_links'                       => [
                    'self' => [
                        'href' => 'http://localhost/api/rest/v1/reference-entities/designer/attributes/birthdate',
                    ],
                ],
            ],
        ];

        $resourceClient
            ->getResource(ReferenceEntityAttributeApi::REFERENCE_ENTITY_ATTRIBUTES_URI, ['designer'])
            ->willReturn($attributes);

        $this->all('designer', [])->shouldReturn($attributes);
    }

    function it_upserts_a_reference_entity_attribute(ResourceClientInterface $resourceClient)
    {
        $attributeData = [
            'code'        => 'description',
            'labels'      => [
                'en_US' => 'Description',
                'fr_FR' => 'Description',
            ],
            'type'        => 'text',
            'localizable' => true,
            'scopable'    => false
        ];

        $resourceClient
            ->upsertResource(ReferenceEntityAttributeApi::REFERENCE_ENTITY_ATTRIBUTE_URI, ['designer', 'description'], $attributeData)
            ->willReturn(204);

        $this->upsert('designer', 'description', $attributeData)->shouldReturn(204);
    }
}
