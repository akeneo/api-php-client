<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Api\ChannelApiInterface;
use Akeneo\Pim\Api\FamilyApiInterface;
use Akeneo\Pim\Api\LocaleApiInterface;
use Akeneo\Pim\Api\MediaFileApiInterface;
use Akeneo\Pim\Api\ProductApiInterface;
use Akeneo\Pim\Client\AkeneoPimClient;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use PhpSpec\ObjectBehavior;

class AkeneoPimClientSpec extends ObjectBehavior
{
    function let(
        ProductApiInterface $productApi,
        CategoryApiInterface $categoryApi,
        AttributeApiInterface $attributeApi,
        AttributeOptionApiInterface $attributeOptionApi,
        FamilyApiInterface $familyApi,
        MediaFileApiInterface $mediaFileApi,
        LocaleApiInterface $localeApi,
        ChannelApiInterface $channelApi
    )
    {
        $this->beConstructedWith($productApi, $categoryApi, $attributeApi, $attributeOptionApi, $familyApi, $mediaFileApi, $localeApi, $channelApi);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(AkeneoPimClientInterface::class);
        $this->shouldHaveType(AkeneoPimClient::class);
    }

    function it_gets_product_api($productApi)
    {
        $this->getProductApi()->shouldReturn($productApi);
    }

    function it_gets_category_api($categoryApi)
    {
        $this->getCategoryApi()->shouldReturn($categoryApi);
    }

    function it_gets_attribute_api($attributeApi)
    {
        $this->getAttributeApi()->shouldReturn($attributeApi);
    }

    function it_gets_attribute_option_api($attributeOptionApi)
    {
        $this->getAttributeOptionApi()->shouldReturn($attributeOptionApi);
    }

    function it_gets_family_api($familyApi)
    {
        $this->getFamilyApi()->shouldReturn($familyApi);
    }

    function it_gets_media_file_api($mediaFileApi)
    {
        $this->getMediaFileApi()->shouldReturn($mediaFileApi);
    }

    function it_gets_locale_api($localeApi)
    {
        $this->getLocaleApi()->shouldReturn($localeApi);
    }

    function it_gets_channel_api($channelApi)
    {
        $this->getChannelApi()->shouldReturn($channelApi);
    }
}