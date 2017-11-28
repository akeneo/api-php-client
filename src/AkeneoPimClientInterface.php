<?php

namespace Akeneo\Pim\ApiClient;

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
use Akeneo\Pim\ApiClient\Api\MediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelApiInterface;

/**
 * Client to use the Akeneo PIM API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AkeneoPimClientInterface
{
    /**
     * Gets the authentication access token
     *
     * @return null|string
     */
    public function getToken();

    /**
     * Gets the authentication refresh token
     *
     * @return null|string
     */
    public function getRefreshToken();

    /**
     * Gets the product API
     *
     * @return ProductApiInterface
     */
    public function getProductApi();

    /**
     * Gets the category API.
     *
     * @return CategoryApiInterface
     */
    public function getCategoryApi();

    /**
     * Gets the attribute API.
     *
     * @return AttributeApiInterface
     */
    public function getAttributeApi();

    /**
     * Gets the attribute option API.
     *
     * @return AttributeOptionApiInterface
     */
    public function getAttributeOptionApi();

    /**
     * Gets the attribute group API.
     *
     * @return AttributeGroupApiInterface
     */
    public function getAttributeGroupApi();

    /**
     * Gets the family API.
     *
     * @return FamilyApiInterface
     */
    public function getFamilyApi();

    /**
     * Gets the product media file API.
     *
     * @return MediaFileApiInterface
     */
    public function getProductMediaFileApi();

    /**
     * Gets the locale API.
     *
     * @return LocaleApiInterface
     */
    public function getLocaleApi();

    /**
     * Gets the channel API.
     *
     * @return ChannelApiInterface
     */
    public function getChannelApi();

    /**
     * Gets the currency API.
     *
     * @return CurrencyApiInterface
     */
    public function getCurrencyApi();

    /**
     * Gets the measure family API.
     *
     * @return MeasureFamilyApiInterface
     */
    public function getMeasureFamilyApi();

    /**
     * Gets the association type API.
     *
     * @return AssociationTypeApiInterface
     */
    public function getAssociationTypeApi();

    /**
     * Gets the family variant API.
     *
     * @return FamilyVariantApiInterface
     */
    public function getFamilyVariantApi();

    /**
     * Gets the product model API.
     *
     * @return ProductModelApiInterface
     */
    public function getProductModelApi();
}
