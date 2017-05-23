<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Client\AkeneoPimClient;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use PhpSpec\ObjectBehavior;

class AkeneoPimClientSpec extends ObjectBehavior
{
    function let(
        CategoryApiInterface $categoryApi,
        AttributeApiInterface $attributeApi
    )
    {
        $this->beConstructedWith($categoryApi, $attributeApi);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(AkeneoPimClientInterface::class);
        $this->shouldHaveType(AkeneoPimClient::class);
    }

    function it_gets_category_api($categoryApi)
    {
        $this->getCategoryApi()->shouldReturn($categoryApi);
    }

    function it_gets_attribute_api($attributeApi)
    {
        $this->getAttributeApi()->shouldReturn($attributeApi);
    }
}
