<?php

namespace spec\Akeneo\Pim;

use Akeneo\Pim\AkeneoPimClient;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Api\AssociationTypeApiInterface;
use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeGroupApi;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Api\ChannelApiInterface;
use Akeneo\Pim\Api\CurrencyApiInterface;
use Akeneo\Pim\Api\FamilyApiInterface;
use Akeneo\Pim\Api\LocaleApiInterface;
use Akeneo\Pim\Api\MeasureFamilyApiInterface;
use Akeneo\Pim\Api\MediaFileApiInterface;
use Akeneo\Pim\Api\ProductApiInterface;
use Akeneo\Pim\Security\Authentication;
use PhpSpec\ObjectBehavior;

class AkeneoPimClientSpec extends ObjectBehavior
{
    function let(
        Authentication $authentication,
        ProductApiInterface $productApi,
        CategoryApiInterface $categoryApi,
        AttributeApiInterface $attributeApi,
        AttributeOptionApiInterface $attributeOptionApi,
        AttributeGroupApi $attributeGroupApi,
        FamilyApiInterface $familyApi,
        MediaFileApiInterface $productMediaFileApi,
        LocaleApiInterface $localeApi,
        ChannelApiInterface $channelApi,
        CurrencyApiInterface $currencyApi,
        MeasureFamilyApiInterface $measureFamilyApi,
        AssociationTypeApiInterface $associationTypeApi
    )
    {
        $this->beConstructedWith(
            $authentication,
            $productApi,
            $categoryApi,
            $attributeApi,
            $attributeOptionApi,
            $attributeGroupApi, $familyApi,
            $productMediaFileApi,
            $localeApi,
            $channelApi,
            $currencyApi,
            $measureFamilyApi,
            $associationTypeApi
        );
    }

    function it_is_initializable()
    {
        $this->shouldImplement(AkeneoPimClientInterface::class);
        $this->shouldHaveType(AkeneoPimClient::class);
    }

    function it_gets_access_token($authentication)
    {
        $authentication->getAccessToken()->willReturn('foo');

        $this->getToken()->shouldReturn('foo');
    }

    function it_gets_refresh_token($authentication)
    {
        $authentication->getRefreshToken()->willReturn('bar');

        $this->getRefreshToken()->shouldReturn('bar');
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

    function it_gets_attribute_group_api($attributeGroupApi)
    {
        $this->getAttributeGroupApi()->shouldReturn($attributeGroupApi);
    }

    function it_gets_family_api($familyApi)
    {
        $this->getFamilyApi()->shouldReturn($familyApi);
    }

    function it_gets_product_media_file_api($productMediaFileApi)
    {
        $this->getProductMediaFileApi()->shouldReturn($productMediaFileApi);
    }

    function it_gets_locale_api($localeApi)
    {
        $this->getLocaleApi()->shouldReturn($localeApi);
    }

    function it_gets_channel_api($channelApi)
    {
        $this->getChannelApi()->shouldReturn($channelApi);
    }

    function it_gets_currency_api($currencyApi)
    {
        $this->getCurrencyApi()->shouldReturn($currencyApi);
    }

    function it_gets_measure_family_api($measureFamilyApi)
    {
        $this->getMeasureFamilyApi()->shouldReturn($measureFamilyApi);
    }

    function it_gets_association_type_api($associationTypeApi)
    {
        $this->getAssociationTypeApi()->shouldReturn($associationTypeApi);
    }
}
