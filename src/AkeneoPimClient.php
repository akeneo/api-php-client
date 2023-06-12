<?php

namespace Akeneo\Pim\ApiClient;

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
use Akeneo\Pim\ApiClient\Api\CategoryMediaFileApi;
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

/**
 * This class is the implementation of the client to use the Akeneo PIM API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    public function __construct(
        protected Authentication $authentication,
        protected ProductApiInterface $productApi,
        protected CategoryApiInterface $categoryApi,
        protected DownloadableResourceInterface $categoryMediaFileApi,
        protected AttributeApiInterface $attributeApi,
        protected AttributeOptionApiInterface $attributeOptionApi,
        protected AttributeGroupApiInterface $attributeGroupApi,
        protected FamilyApiInterface $familyApi,
        protected MediaFileApiInterface $productMediaFileApi,
        protected LocaleApiInterface $localeApi,
        protected ChannelApiInterface $channelApi,
        protected CurrencyApiInterface $currencyApi,
        protected MeasureFamilyApiInterface $measureFamilyApi,
        private MeasurementFamilyApiInterface $measurementFamilyApi,
        protected AssociationTypeApiInterface $associationTypeApi,
        protected FamilyVariantApiInterface $familyVariantApi,
        protected ProductModelApiInterface $productModelApi,
        private ProductModelDraftApiInterface $productModelDraftApi,
        private PublishedProductApiInterface $publishedProductApi,
        private ProductDraftApiInterface $productDraftApi,
        private AssetApiInterface $assetApi,
        private AssetCategoryApiInterface $assetCategoryApi,
        private AssetTagApiInterface $assetTagApi,
        private AssetReferenceFileApiInterface $assetReferenceFileApi,
        private AssetVariationFileApiInterface $assetVariationFileApi,
        private ReferenceEntityRecordApiInterface $referenceEntityRecordApi,
        private ReferenceEntityMediaFileApiInterface $referenceEntityMediaFileApi,
        private ReferenceEntityAttributeApiInterface $referenceEntityAttributeApi,
        private ReferenceEntityAttributeOptionApiInterface $referenceEntityAttributeOptionApi,
        private ReferenceEntityApiInterface $referenceEntityApi,
        private AssetManagerApiInterface $assetManagerApi,
        private AssetFamilyApiInterface $assetFamilyApi,
        private AssetAttributeApiInterface $assetAttributeApi,
        private AssetAttributeOptionApiInterface $assetAttributeOptionApi,
        private AssetMediaFileApiInterface $assetMediaFileApi,
        private ProductUuidApiInterface $productUuidApi,
        private ProductDraftUuidApiInterface $productDraftUuidApi,
        private AppCatalogApiInterface $appCatalogApi,
        private AppCatalogProductApiInterface $appCatalogProductApi
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): ?string
    {
        return $this->authentication->getAccessToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken(): ?string
    {
        return $this->authentication->getRefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductApi(): ProductApiInterface
    {
        return $this->productApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryApi(): CategoryApiInterface
    {
        return $this->categoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryMediaFileApi(): DownloadableResourceInterface
    {
        return $this->categoryMediaFileApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeApi(): AttributeApiInterface
    {
        return $this->attributeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeOptionApi(): AttributeOptionApiInterface
    {
        return $this->attributeOptionApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeGroupApi(): AttributeGroupApiInterface
    {
        return $this->attributeGroupApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getFamilyApi(): FamilyApiInterface
    {
        return $this->familyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductMediaFileApi(): MediaFileApiInterface
    {
        return $this->productMediaFileApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleApi(): LocaleApiInterface
    {
        return $this->localeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelApi(): ChannelApiInterface
    {
        return $this->channelApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyApi(): CurrencyApiInterface
    {
        return $this->currencyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeasureFamilyApi(): MeasureFamilyApiInterface
    {
        return $this->measureFamilyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeasurementFamilyApi(): MeasurementFamilyApiInterface
    {
        return $this->measurementFamilyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationTypeApi(): AssociationTypeApiInterface
    {
        return $this->associationTypeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getFamilyVariantApi(): FamilyVariantApiInterface
    {
        return $this->familyVariantApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductModelApi(): ProductModelApiInterface
    {
        return $this->productModelApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedProductApi(): PublishedProductApiInterface
    {
        return $this->publishedProductApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductModelDraftApi(): ProductModelDraftApiInterface
    {
        return $this->productModelDraftApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductDraftApi(): ProductDraftApiInterface
    {
        return $this->productDraftApi;
    }

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     * @see getAssetManagerApi instead.
     */
    public function getAssetApi(): AssetApiInterface
    {
        return $this->assetApi;
    }

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     * @see getAssetFamilyApi instead.
     */
    public function getAssetCategoryApi(): AssetCategoryApiInterface
    {
        return $this->assetCategoryApi;
    }

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetTagApi(): AssetTagApiInterface
    {
        return $this->assetTagApi;
    }

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetReferenceFileApi(): AssetReferenceFileApiInterface
    {
        return $this->assetReferenceFileApi;
    }

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetVariationFileApi(): AssetVariationFileApiInterface
    {
        return $this->assetVariationFileApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntityRecordApi(): ReferenceEntityRecordApiInterface
    {
        return $this->referenceEntityRecordApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntityMediaFileApi(): ReferenceEntityMediaFileApiInterface
    {
        return $this->referenceEntityMediaFileApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntityAttributeApi(): ReferenceEntityAttributeApiInterface
    {
        return $this->referenceEntityAttributeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntityAttributeOptionApi(): ReferenceEntityAttributeOptionApiInterface
    {
        return $this->referenceEntityAttributeOptionApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntityApi(): ReferenceEntityApiInterface
    {
        return $this->referenceEntityApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetManagerApi(): AssetManagerApiInterface
    {
        return $this->assetManagerApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetFamilyApi(): AssetFamilyApiInterface
    {
        return $this->assetFamilyApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetAttributeApi(): AssetAttributeApiInterface
    {
        return $this->assetAttributeApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetAttributeOptionApi(): AssetAttributeOptionApiInterface
    {
        return $this->assetAttributeOptionApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetMediaFileApi(): AssetMediaFileApiInterface
    {
        return $this->assetMediaFileApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getProductUuidApi(): ProductUuidApiInterface
    {
        return $this->productUuidApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getProductDraftUuidApi(): ProductDraftUuidApiInterface
    {
        return $this->productDraftUuidApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAppCatalogApi(): AppCatalogApiInterface
    {
        return $this->appCatalogApi;
    }

    /**
     * {@inheritDoc}
     */
    public function getAppCatalogProductApi(): AppCatalogProductApiInterface
    {
        return $this->appCatalogProductApi;
    }
}
