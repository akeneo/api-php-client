<?php

namespace spec\Akeneo\Pim\ApiClient;

use Akeneo\Pim\ApiClient\AkeneoPimClient;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AssociationTypeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApi;
use Akeneo\Pim\ApiClient\Api\AttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\CategoryApiInterface;
use Akeneo\Pim\ApiClient\Api\ChannelApiInterface;
use Akeneo\Pim\ApiClient\Api\CurrencyApiInterface;
use Akeneo\Pim\ApiClient\Api\FamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\FamilyVariantApiInterface;
use Akeneo\Pim\ApiClient\Api\LocaleApiInterface;
use Akeneo\Pim\ApiClient\Api\MeasureFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\MeasurementFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\MediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelApiInterface;
use Akeneo\Pim\ApiClient\Security\Authentication;
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
        MeasurementFamilyApiInterface $measurementFamilyApi,
        AssociationTypeApiInterface $associationTypeApi,
        FamilyVariantApiInterface $familyVariantApi,
        ProductModelApiInterface $productModelApi
    ) {
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
            $measurementFamilyApi,
            $associationTypeApi,
            $familyVariantApi,
            $productModelApi
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

    function it_gets_family_variant_api($familyVariantApi)
    {
        $this->getFamilyVariantApi()->shouldReturn($familyVariantApi);
    }

    function it_gets_product_model_api($productModelApi)
    {
        $this->getProductModelApi()->shouldReturn($productModelApi);
    }
}
