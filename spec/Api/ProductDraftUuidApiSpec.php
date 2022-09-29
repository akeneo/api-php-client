<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApi;
use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class ProductDraftUuidApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductDraftUuidApi::class);
        $this->shouldImplement(ProductDraftUuidApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
    }

    function it_gets_a_product_draft($resourceClient)
    {
        $draft = [
            'uuid' => '944ca210-d8e0-4c57-9529-741e17e95c8d',
            'family' => 'bar',
            'parent' => null,
            'groups' => [],
            'categories' => [],
            'enabled' => true,
            'values' => [],
            'created' => 'this is a date formatted to ISO-8601',
            'updated' => 'this is a date formatted to ISO-8601',
            'associations' => [],
            'metadata' => [
                'workflow_status' => 'draft_in_progress',
            ],
        ];

        $resourceClient->getResource(ProductDraftUuidApi::PRODUCT_DRAFT_UUID_URI, ['944ca210-d8e0-4c57-9529-741e17e95c8d'])->willReturn($draft);

        $this->get('944ca210-d8e0-4c57-9529-741e17e95c8d')->shouldReturn($draft);
    }

    function it_submits_a_product_draft_for_approval($resourceClient)
    {
        $resourceClient->createResource(ProductDraftUuidApi::PRODUCT_PROPOSAL_UUID_URI, ['944ca210-d8e0-4c57-9529-741e17e95c8d'])->willReturn(201);

        $this->submitForApproval('944ca210-d8e0-4c57-9529-741e17e95c8d')->shouldReturn(201);
    }
}
