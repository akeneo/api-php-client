<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Api\ChannelApiInterface;
use Akeneo\Pim\Api\FamilyApiInterface;
use Akeneo\Pim\Api\LocaleApiInterface;
use Akeneo\Pim\Api\MediaFileApiInterface;
use Akeneo\Pim\Api\ProductApiInterface;

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
     * Gets the product API
     *
     * @return ProductApiInterface
     */
    public function getProductAPi();

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
     * Gets the family API.
     *
     * @return FamilyApiInterface
     */
    public function getFamilyApi();

    /**
     * Gets the media file API.
     *
     * @return MediaFileApiInterface
     */
    public function getMediaFileApi();

    /**
     * Gets the locale API.
     *
     * @return LocaleApiInterface
     */
    public function getLocaleAPi();

    /**
     * Gets the channel API.
     *
     * @return ChannelApiInterface
     */
    public function getChannelAPi();
}
