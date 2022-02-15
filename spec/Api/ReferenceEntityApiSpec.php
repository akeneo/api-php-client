<?php

declare(strict_types=1);

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class ReferenceEntityApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_a_reference_entity_api()
    {
        $this->shouldImplement(ReferenceEntityApiInterface::class);
    }

    function it_returns_a_reference_entity(ResourceClientInterface $resourceClient)
    {
        $referenceEntity = [
            '_links' => [
                'image_download' => [
                    'href' => 'https://localhost/api/rest/v1/reference-entities-media-files/img.png'
              ]
            ],
            'code' => 'designer',
            'labels' => [
                'en_US' => 'Designer'
            ],
            'image' => 'img.png'
        ];

        $resourceClient
            ->getResource(ReferenceEntityApi::REFERENCE_ENTITY_URI, ['designer'])
            ->willReturn($referenceEntity);

        $this->get('designer')->shouldReturn($referenceEntity);
    }

    function it_returns_a_cursor_to_list_all_the_reference_entities(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ReferenceEntityApi::REFERENCE_ENTITIES_URI, [], null, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);
        $cursorFactory->createCursor(null, $page)->willReturn($cursor);

        $this->all()->shouldReturn($cursor);
    }

    function it_upserts_a_reference_entity(ResourceClientInterface $resourceClient)
    {
        $referenceEntityData = [
            'code' => 'designer',
            'labels' => [
                'en_US' => 'Designer'
            ]
        ];
        $resourceClient
            ->upsertResource(ReferenceEntityApi::REFERENCE_ENTITY_URI, ['designer'], $referenceEntityData)
            ->willReturn(204);

        $this->upsert('designer', $referenceEntityData)->shouldReturn(204);
    }
}
