<?php

namespace Akeneo\Pim\ApiClient;

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
use Akeneo\Pim\ApiClient\Api\ProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftApiInterface;
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
    /** @var Authentication */
    protected $authentication;

    /** @var ProductApiInterface */
    protected $productApi;

    /** @var CategoryApiInterface */
    protected $categoryApi;

    /** @var AttributeApiInterface */
    protected $attributeApi;

    /** @var AttributeOptionApiInterface */
    protected $attributeOptionApi;

    /** @var AttributeGroupApiInterface */
    protected $attributeGroupApi;

    /** @var FamilyApiInterface */
    protected $familyApi;

    /** @var MediaFileApiInterface */
    protected $productMediaFileApi;

    /** @var LocaleApiInterface */
    protected $localeApi;

    /** @var ChannelApiInterface */
    protected $channelApi;

    /** @var CurrencyApiInterface */
    protected $currencyApi;

    /** @var MeasureFamilyApiInterface */
    protected $measureFamilyApi;

    /** @var AssociationTypeApiInterface */
    protected $associationTypeApi;

    /** @var FamilyVariantApiInterface */
    protected $familyVariantApi;

    /** @var ProductModelApiInterface */
    protected $productModelApi;

    /** @var MeasurementFamilyApiInterface */
    private $measurementFamilyApi;

    /** @var PublishedProductApiInterface */
    private $publishedProductApi;

    /** @var ProductModelDraftApiInterface */
    private $productModelDraftApi;

    /** @var ProductDraftApiInterface */
    private $productDraftApi;

    /** @var AssetApiInterface */
    private $assetApi;

    /** @var AssetCategoryApiInterface */
    private $assetCategoryApi;

    /** @var AssetTagApiInterface */
    private $assetTagApi;

    /** @var AssetReferenceFileApiInterface */
    private $assetReferenceFileApi;

    /** @var AssetVariationFileApiInterface */
    private $assetVariationFileApi;

    /** @var ReferenceEntityRecordApiInterface */
    private $referenceEntityRecordApi;

    /** @var ReferenceEntityMediaFileApiInterface */
    private $referenceEntityMediaFileApi;

    /** @var ReferenceEntityAttributeApiInterface */
    private $referenceEntityAttributeApi;

    /** @var ReferenceEntityAttributeOptionApiInterface */
    private $referenceEntityAttributeOptionApi;

    /** @var ReferenceEntityApiInterface */
    private $referenceEntityApi;

    /** @var AssetManagerApiInterface */
    private $assetManagerApi;

    /** @var AssetFamilyApiInterface */
    private $assetFamilyApi;

    /** @var AssetAttributeApiInterface */
    private $assetAttributeApi;

    /** @var AssetAttributeOptionApiInterface */
    private $assetAttributeOptionApi;

    /** @var AssetMediaFileApiInterface */
    private $assetMediaFileApi;

    private ProductUuidApiInterface $productUuidApi;

    public function __construct(
        Authentication $authentication,
        ProductApiInterface $productApi,
        CategoryApiInterface $categoryApi,
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
        ProductUuidApiInterface $productUuidApi
    ) {
        $this->authentication = $authentication;
        $this->productApi = $productApi;
        $this->categoryApi = $categoryApi;
        $this->attributeApi = $attributeApi;
        $this->attributeOptionApi = $attributeOptionApi;
        $this->attributeGroupApi = $attributeGroupApi;
        $this->familyApi = $familyApi;
        $this->productMediaFileApi = $productMediaFileApi;
        $this->localeApi = $localeApi;
        $this->channelApi = $channelApi;
        $this->currencyApi = $currencyApi;
        $this->measureFamilyApi = $measureFamilyApi;
        $this->measurementFamilyApi = $measurementFamilyApi;
        $this->associationTypeApi = $associationTypeApi;
        $this->familyVariantApi = $familyVariantApi;
        $this->productModelApi = $productModelApi;
        $this->publishedProductApi = $publishedProductApi;
        $this->productDraftApi = $productDraftApi;
        $this->productModelDraftApi = $productModelDraftApi;
        $this->assetApi = $assetApi;
        $this->assetCategoryApi = $assetCategoryApi;
        $this->assetTagApi = $assetTagApi;
        $this->assetReferenceFileApi = $assetReferenceFileApi;
        $this->assetVariationFileApi = $assetVariationFileApi;
        $this->referenceEntityRecordApi = $referenceEntityRecordApi;
        $this->referenceEntityMediaFileApi = $referenceEntityMediaFileApi;
        $this->referenceEntityAttributeApi = $referenceEntityAttributeApi;
        $this->referenceEntityAttributeOptionApi = $referenceEntityAttributeOptionApi;
        $this->referenceEntityApi = $referenceEntityApi;
        $this->assetManagerApi = $assetManagerApi;
        $this->assetFamilyApi = $assetFamilyApi;
        $this->assetAttributeApi = $assetAttributeApi;
        $this->assetAttributeOptionApi = $assetAttributeOptionApi;
        $this->assetMediaFileApi = $assetMediaFileApi;
        $this->productUuidApi = $productUuidApi;
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
     * @return AssetApiInterface
     */
    public function getAssetApi(): AssetApiInterface
    {
        return $this->assetApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetCategoryApi(): AssetCategoryApiInterface
    {
        return $this->assetCategoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetTagApi(): AssetTagApiInterface
    {
        return $this->assetTagApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetReferenceFileApi(): AssetReferenceFileApiInterface
    {
        return $this->assetReferenceFileApi;
    }

    /**
     * {@inheritdoc}
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
    public function getProductUuidApi() : ProductUuidApiInterface
    {
        return $this->productUuidApi;
    }
}
