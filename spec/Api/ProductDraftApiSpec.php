<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftApi;
use Akeneo\Pim\ApiClient\Api\ProductDraftApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class ProductDraftApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(ProductDraftApi::class);
        $this->shouldImplement(ProductDraftApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
    }

    function it_gets_a_product_draft($resourceClient)
    {
        $draft = [
            'identifier' => 'foo',
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

        $resourceClient->getResource(ProductDraftApi::PRODUCT_DRAFT_URI, ['foo'])->willReturn($draft);

        $this->get('foo')->shouldReturn($draft);
    }

    function it_submits_a_product_draft_for_approval($resourceClient)
    {
        $resourceClient->createResource(ProductDraftApi::PRODUCT_PROPOSAL_URI, ['foo'])->willReturn(201);

        $this->submitForApproval('foo')->shouldReturn(201);
    }
}
