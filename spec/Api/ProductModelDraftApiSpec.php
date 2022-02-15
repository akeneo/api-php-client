<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelDraftApi;
use Akeneo\Pim\ApiClient\Api\ProductModelDraftApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class ProductModelDraftApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(ProductModelDraftApi::class);
        $this->shouldImplement(ProductModelDraftApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
    }

    function it_gets_a_product_model_draft($resourceClient)
    {
        $draft = [
            'identifier' => 'a_product_model',
            'family_variant' => 'bar',
            'parent' => null,
            'values' => [],
            'created' => 'this is a date formatted to ISO-8601',
            'updated' => 'this is a date formatted to ISO-8601',
            'associations' => [],
            'metadata' => [
                'workflow_status' => 'draft_in_progress',
            ],
        ];

        $resourceClient->getResource(ProductModelDraftApi::PRODUCT_MODEL_DRAFT_URI, ['a_product_model'])->willReturn($draft);

        $this->get('a_product_model')->shouldReturn($draft);
    }

    function it_submits_a_product_model_draft_for_approval($resourceClient)
    {
        $resourceClient->createResource(ProductModelDraftApi::PRODUCT_MODEL_PROPOSAL_URI, ['a_product_model'])->willReturn(201);

        $this->submitForApproval('a_product_model')->shouldReturn(201);
    }
}
