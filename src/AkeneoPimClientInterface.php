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

/**
 * Client to use the Akeneo PIM API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AkeneoPimClientInterface
{
    public function getToken(): ?string;

    public function getRefreshToken(): ?string;

    public function getProductApi(): ProductApiInterface;

    public function getCategoryApi(): CategoryApiInterface;

    public function getCategoryMediaFileApi(): DownloadableResourceInterface;

    public function getAttributeApi(): AttributeApiInterface;

    public function getAttributeOptionApi(): AttributeOptionApiInterface;

    public function getAttributeGroupApi(): AttributeGroupApiInterface;

    public function getFamilyApi(): FamilyApiInterface;

    public function getProductMediaFileApi(): MediaFileApiInterface;

    public function getLocaleApi(): LocaleApiInterface;

    public function getChannelApi(): ChannelApiInterface;

    public function getCurrencyApi(): CurrencyApiInterface;

    public function getMeasureFamilyApi(): MeasureFamilyApiInterface;

    public function getMeasurementFamilyApi(): MeasurementFamilyApiInterface;

    public function getAssociationTypeApi(): AssociationTypeApiInterface;

    public function getFamilyVariantApi(): FamilyVariantApiInterface;

    public function getProductModelApi(): ProductModelApiInterface;

    public function getPublishedProductApi(): PublishedProductApiInterface;

    public function getProductModelDraftApi(): ProductModelDraftApiInterface;

    public function getProductDraftApi(): ProductDraftApiInterface;

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     * @see getAssetManagerApi instead.
     */
    public function getAssetApi(): AssetApiInterface;

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     * @see getAssetFamilyApi instead.
     */
    public function getAssetCategoryApi(): AssetCategoryApiInterface;

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetTagApi(): AssetTagApiInterface;

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetReferenceFileApi(): AssetReferenceFileApiInterface;

    /**
     * @deprecated Route unavailable in latest PIM versions. Will be removed in v12.0.0.
     */
    public function getAssetVariationFileApi(): AssetVariationFileApiInterface;

    public function getReferenceEntityRecordApi(): ReferenceEntityRecordApiInterface;

    public function getReferenceEntityMediaFileApi(): ReferenceEntityMediaFileApiInterface;

    public function getReferenceEntityAttributeApi(): ReferenceEntityAttributeApiInterface;

    public function getReferenceEntityAttributeOptionApi(): ReferenceEntityAttributeOptionApiInterface;

    public function getReferenceEntityApi(): ReferenceEntityApiInterface;

    public function getAssetManagerApi(): AssetManagerApiInterface;

    public function getAssetFamilyApi(): AssetFamilyApiInterface;

    public function getAssetAttributeApi(): AssetAttributeApiInterface;

    public function getAssetAttributeOptionApi(): AssetAttributeOptionApiInterface;

    public function getAssetMediaFileApi(): AssetMediaFileApiInterface;

    public function getProductUuidApi(): ProductUuidApiInterface;

    public function getProductDraftUuidApi(): ProductDraftUuidApiInterface;

    public function getAppCatalogApi(): AppCatalogApiInterface;

    public function getAppCatalogProductApi(): AppCatalogProductApiInterface;
}
