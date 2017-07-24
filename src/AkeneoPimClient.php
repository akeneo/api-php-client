<?php

namespace Akeneo\Pim;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeGroupApiInterface;
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

    /**
     * @param Authentication              $authentication
     * @param ProductApiInterface         $productApi
     * @param CategoryApiInterface        $categoryApi
     * @param AttributeApiInterface       $attributeApi
     * @param AttributeOptionApiInterface $attributeOptionApi
     * @param AttributeGroupApiInterface  $attributeGroupApi
     * @param FamilyApiInterface          $familyApi
     * @param MediaFileApiInterface       $productMediaFileApi
     * @param LocaleApiInterface          $localeApi
     * @param ChannelApiInterface         $channelApi
     * @param CurrencyApiInterface        $currencyApi
     * @param MeasureFamilyApiInterface   $measureFamilyApi
     */
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
        CurrencyApiInterface $currencyApi
        ChannelApiInterface $channelApi,
        MeasureFamilyApiInterface $measureFamilyApi
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
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->authentication->getAccessToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        return $this->authentication->getRefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductApi()
    {
        return $this->productApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryApi()
    {
        return $this->categoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeApi()
    {
        return $this->attributeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeOptionApi()
    {
        return $this->attributeOptionApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeGroupApi()
    {
        return $this->attributeGroupApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getFamilyApi()
    {
        return $this->familyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductMediaFileApi()
    {
        return $this->productMediaFileApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleApi()
    {
        return $this->localeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelApi()
    {
        return $this->channelApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyApi()
    {
        return $this->currencyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeasureFamilyApi()
    {
        return $this->measureFamilyApi;
    }
}
