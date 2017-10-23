<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\ProductModelApi;
use Akeneo\Pim\Api\ProductModelApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use PhpSpec\ObjectBehavior;

class ProductModelApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient)
    {
        $this->beConstructedWith($resourceClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductModelApi::class);
        $this->shouldImplement(ProductModelApiInterface::class);
    }

    function it_returns_a_product_model($resourceClient)
    {
        $productModel = [
            'code' => 'a_product_model',
            'parent' => null
        ];

        $resourceClient
            ->getResource(ProductModelApi::PRODUCT_MODEL_URI, ['a_product_model'])
            ->willReturn($productModel);

        $this->get('a_product_model')->shouldReturn($productModel);
    }
}
