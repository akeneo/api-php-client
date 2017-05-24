<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Api\ChannelApiInterface;
use Akeneo\Pim\Api\FamilyApiInterface;
use Akeneo\Pim\Api\LocaleApiInterface;
use Akeneo\Pim\Api\MediaFileApiInterface;

/**
 * This class is the implementation of the client to use the Akeneo PIM API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    /** @var CategoryApiInterface */
    protected $categoryApi;

    /** @var AttributeApiInterface */
    protected $attributeApi;

    /** @var AttributeOptionApiInterface */
    protected $attributeOptionApi;

    /** @var FamilyApiInterface */
    protected $familyApi;

    /** @var MediaFileApiInterface */
    protected $mediaFileAPi;

    /** @var LocaleApiInterface */
    protected $localeApi;

    /** @var ChannelApiInterface */
    protected $channelApi;

    /**
     * @param CategoryApiInterface        $categoryApi
     * @param AttributeApiInterface       $attributeApi
     * @param AttributeOptionApiInterface $attributeOptionApi
     * @param FamilyApiInterface          $familyApi
     * @param MediaFileApiInterface       $mediaFileAPi
     * @param LocaleApiInterface          $localeApi
     * @param ChannelApiInterface         $channelApi
     */
    public function __construct(
        CategoryApiInterface $categoryApi,
        AttributeApiInterface $attributeApi,
        AttributeOptionApiInterface $attributeOptionApi,
        FamilyApiInterface $familyApi,
        MediaFileApiInterface $mediaFileAPi,
        LocaleApiInterface $localeApi,
        ChannelApiInterface $channelApi
    )
    {
        $this->categoryApi = $categoryApi;
        $this->attributeApi = $attributeApi;
        $this->attributeOptionApi = $attributeOptionApi;
        $this->familyApi = $familyApi;
        $this->mediaFileAPi = $mediaFileAPi;
        $this->localeApi = $localeApi;
        $this->channelApi = $channelApi;
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
    public function getFamilyApi()
    {
        return $this->familyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFileApi()
    {
        return $this->mediaFileAPi;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleAPi()
    {
        return $this->localeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannelAPi()
    {
        return $this->channelApi;
    }
}
