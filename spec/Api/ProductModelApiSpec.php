<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\ProductModelApi;
use Akeneo\Pim\Api\ProductModelApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\InvalidArgumentException;
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

    function it_creates_a_product_model($resourceClient)
    {
        $code = 'a_product_model';
        $productModel = ['parent' => null];

        $resourceClient
            ->createResource(
                ProductModelApi::PRODUCT_MODELS_URI,
                [],
                ['code' => $code, 'parent' => null]
            )
            ->shouldBeCalled()
            ->willReturn(201);

        $this->create($code, $productModel)->shouldReturn(201);
    }

    function it_throws_an_exception_if_the_code_is_sent_in_data()
    {
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->during('create', ['a_product_model', ['code' => 'product_model']]);
    }
}
