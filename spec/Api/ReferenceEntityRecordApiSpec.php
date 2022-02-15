<?php

declare(strict_types=1);

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class ReferenceEntityRecordApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_a_reference_entity_record_api()
    {
        $this->shouldImplement(ReferenceEntityRecordApiInterface::class);
    }

    function it_returns_a_reference_entity_record(ResourceClientInterface $resourceClient)
    {
        $record = [
            'code' => 'starck',
            'values' => [
                'label' => [
                    [
                        'channel' => null,
                        'locale'  => 'en_US',
                        'data'    => 'Philippe Starck'
                    ],
                ]
            ]
        ];

        $resourceClient
            ->getResource(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORD_URI, ['designer', 'starck'])
            ->willReturn($record);

        $this->get('designer', 'starck')->shouldReturn($record);
    }

    function it_returns_a_cursor_to_list_all_the_records_of_reference_entity(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORDS_URI, ['designer'], null, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);
        $cursorFactory->createCursor(null, $page)->willReturn($cursor);

        $this->all('designer', [])->shouldReturn($cursor);
    }

    function it_upserts_a_reference_entity_record(ResourceClientInterface $resourceClient)
    {
        $recordData = [
            'code' => 'starck',
            'values' => [
                'label' => [
                    [
                        'channel' => null,
                        'locale'  => 'en_US',
                        'data'    => 'Philippe Starck'
                    ],
                ]
            ]
        ];
        $resourceClient
            ->upsertResource(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORD_URI, ['designer', 'starck'], $recordData)
            ->willReturn(204);

        $this->upsert('designer', 'starck', $recordData)->shouldReturn(204);
    }

    function it_upserts_a_list_of_reference_entity_records(ResourceClientInterface $resourceClient)
    {
        $records = [
            [
                'code' => 'starck',
                'values' => [
                    'label' => [
                        [
                            'channel' => null,
                            'locale'  => 'en_US',
                            'data'    => 'Philippe Starck'
                        ],
                    ]
                ]
            ],
            [
                'code' => 'dyson',
                'values' => [
                    'label' => [
                        [
                            'channel' => null,
                            'locale'  => 'en_US',
                            'data'    => 'James Dyson'
                        ],
                    ]
                ]
            ]
        ];

        $responses = [
            [
                'code' => 'starck',
                'status_code' =>204
            ],
            [
                'code' => 'dyson',
                'status_code' =>201
            ],
        ];

        $resourceClient
            ->upsertJsonResourceList(ReferenceEntityRecordApi::REFERENCE_ENTITY_RECORDS_URI, ['designer'], $records)
            ->willReturn($responses);

        $this->upsertList('designer', $records)->shouldReturn($responses);
    }
}
