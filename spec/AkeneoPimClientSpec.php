<?php

namespace spec\Akeneo\Pim\ApiClient;

use Akeneo\Pim\ApiClient\AkeneoPimClient;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApiInterface;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetCategoryApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApiInterface as AssetManagerApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetTagApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetVariationFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssociationTypeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApiInterface;
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
use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelDraftApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductUuidApiInterface;
use Akeneo\Pim\ApiClient\Api\PublishedProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApiInterface;
use Akeneo\Pim\ApiClient\Security\Authentication;
use PhpSpec\ObjectBehavior;

class AkeneoPimClientSpec extends ObjectBehavior
{
    function let(
        Authentication $authentication,
        ProductApiInterface $productApi,
        CategoryApiInterface $categoryApi,
        DownloadableResourceInterface $categoryMediaFileApi,
        AttributeApiInterface $attributeApi,
        AttributeOptionApiInterface $attributeOptionApi,
        AttributeGroupApiInterface $attributeGroupApi,
        FamilyApiInterface $familyApi,
        MediaFileApiInterface $productMediaFileApi,
        LocaleApiInterface $localeApi,
        ChannelApiInterface $channelApi,
        CurrencyApiInterface $currencyApi,
        MeasureFamilyApiInterface $measureFamilyApi,
        MeasurementFamilyApiInterface $measurementFamilyApi,
        AssociationTypeApiInterface $associationTypeApi,
        FamilyVariantApiInterface $familyVariantApi,
        ProductModelApiInterface $productModelApi,
        ProductModelDraftApiInterface $productModelDraftApi,
        PublishedProductApiInterface $publishedProductApi,
        ProductDraftApiInterface $productDraftApi,
        AssetApiInterface $assetApi,
        AssetCategoryApiInterface $assetCategoryApi,
        AssetTagApiInterface $assetTagApi,
        AssetReferenceFileApiInterface $assetReferenceFileApi,
        AssetVariationFileApiInterface $assetVariationFileApi,
        ReferenceEntityRecordApiInterface $referenceEntityRecordApi,
        ReferenceEntityMediaFileApiInterface $referenceEntityMediaFileApi,
        ReferenceEntityAttributeApiInterface $referenceEntityAttributeApi,
        ReferenceEntityAttributeOptionApiInterface $referenceEntityAttributeOptionApi,
        ReferenceEntityApiInterface $referenceEntityApi,
        AssetManagerApiInterface $assetManagerApi,
        AssetFamilyApiInterface $assetFamilyApi,
        AssetAttributeApiInterface $assetAttributeApi,
        AssetAttributeOptionApiInterface $assetAttributeOptionApi,
        AssetMediaFileApiInterface $assetMediaFileApi,
        ProductUuidApiInterface $productUuidApi,
        ProductDraftUuidApiInterface $productDraftUuidApi,
        AppCatalogApiInterface $appCatalogApi,
        AppCatalogProductApiInterface $appCatalogProductApi
    ) {
        $this->beConstructedWith(
            $authentication,
            $productApi,
            $categoryApi,
            $categoryMediaFileApi,
            $attributeApi,
            $attributeOptionApi,
            $attributeGroupApi,
            $familyApi,
            $productMediaFileApi,
            $localeApi,
            $channelApi,
            $currencyApi,
            $measureFamilyApi,
            $measurementFamilyApi,
            $associationTypeApi,
            $familyVariantApi,
            $productModelApi,
            $productModelDraftApi,
            $publishedProductApi,
            $productDraftApi,
            $assetApi,
            $assetCategoryApi,
            $assetTagApi,
            $assetReferenceFileApi,
            $assetVariationFileApi,
            $referenceEntityRecordApi,
            $referenceEntityMediaFileApi,
            $referenceEntityAttributeApi,
            $referenceEntityAttributeOptionApi,
            $referenceEntityApi,
            $assetManagerApi,
            $assetFamilyApi,
            $assetAttributeApi,
            $assetAttributeOptionApi,
            $assetMediaFileApi,
            $productUuidApi,
            $productDraftUuidApi,
            $appCatalogApi,
            $appCatalogProductApi
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

    function it_gets_category_media_file_api($categoryMediaFileApi)
    {
        $this->getCategoryMediaFileApi()->shouldReturn($categoryMediaFileApi);
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

    function it_gets_published_product_api($publishedProductApi)
    {
        $this->getPublishedProductApi()->shouldReturn($publishedProductApi);
    }

    function it_gets_draft_product_api($productDraftApi)
    {
        $this->getProductDraftApi()->shouldReturn($productDraftApi);
    }

    function it_gets_draft_product_model_api($productModelDraftApi)
    {
        $this->getProductModelDraftApi()->shouldReturn($productModelDraftApi);
    }

    function it_gets_asset_api($assetApi)
    {
        $this->getAssetApi()->shouldReturn($assetApi);
    }

    function it_gets_asset_category_api($assetCategoryApi)
    {
        $this->getAssetCategoryApi()->shouldReturn($assetCategoryApi);
    }

    function it_gets_asset_tags_api($assetTagApi)
    {
        $this->getAssetTagApi()->shouldReturn($assetTagApi);
    }

    function it_gets_asset_reference_file_api($assetReferenceFileApi)
    {
        $this->getAssetReferenceFileApi()->shouldReturn($assetReferenceFileApi);
    }

    function it_gets_reference_entity_record_api($referenceEntityRecordApi)
    {
        $this->getReferenceEntityRecordApi()->shouldReturn($referenceEntityRecordApi);
    }

    function it_gets_reference_entity_media_file_api($referenceEntityMediaFileApi)
    {
        $this->getReferenceEntityMediaFileApi()->shouldReturn($referenceEntityMediaFileApi);
    }

    function it_gets_reference_entity_attribute_api($referenceEntityAttributeApi)
    {
        $this->getReferenceEntityAttributeApi()->shouldReturn($referenceEntityAttributeApi);
    }

    function it_gets_reference_entity_api($referenceEntityApi)
    {
        $this->getReferenceEntityApi()->shouldReturn($referenceEntityApi);
    }

    function it_gets_asset_manager_api($assetManagerApi)
    {
        $this->getAssetManagerApi()->shouldReturn($assetManagerApi);
    }

    function it_gets_asset_family_api($assetFamilyApi)
    {
        $this->getAssetFamilyApi()->shouldReturn($assetFamilyApi);
    }

    function it_gets_asset_attribute_api($assetAttributeApi)
    {
        $this->getAssetAttributeApi()->shouldReturn($assetAttributeApi);
    }

    function it_gets_asset_attribute_option_api($assetAttributeOptionApi)
    {
        $this->getAssetAttributeOptionApi()->shouldReturn($assetAttributeOptionApi);
    }

    function it_gets_asset_media_file_api($assetMediaFileApi)
    {
        $this->getAssetMediaFileApi()->shouldReturn($assetMediaFileApi);
    }

    function it_gets_product_uuid_api(ProductUuidApiInterface $productUuidApi)
    {
        $this->getProductUuidApi()->shouldReturn($productUuidApi);
    }

    function it_gets_product_draft_uuid_api(ProductDraftUuidApiInterface $productDraftUuidApi)
    {
        $this->getProductDraftUuidApi()->shouldReturn($productDraftUuidApi);
    }

    function it_gets_app_catalog_api(AppCatalogApiInterface $appCatalogApi)
    {
        $this->getAppCatalogApi()->shouldReturn($appCatalogApi);
    }

    function it_gets_app_catalog_product_api(AppCatalogProductApiInterface $appCatalogProductApi)
    {
        $this->getAppCatalogProductApi()->shouldReturn($appCatalogProductApi);
    }
}
