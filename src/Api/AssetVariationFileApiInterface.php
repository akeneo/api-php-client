<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Psr\Http\Message\ResponseInterface;

/**
 * API to manage asset variation files.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @deprecated Route unavailable in latest PIM versions. Class will be removed in v12.0.0.
 */
interface AssetVariationFileApiInterface
{
    /**
     * Available since Akeneo PIM 2.1.
     * Gets an asset variation file by its asset code, channel code and local code for a localizable asset.
     *
     * @param string $assetCode   code of the asset
     * @param string $channelCode code of the channel
     * @param string $localeCode  code of the locale
     *
     * @return array
     */
    public function getFromLocalizableAsset(string $assetCode, string $channelCode, string $localeCode): array;

    /**
     * Available since Akeneo PIM 2.1.
     * Gets an asset variation file by its asset code and channel code for a not localizable asset.
     *
     * @param string $assetCode   code of the asset
     * @param string $channelCode code of the channel
     *
     * @return array
     */
    public function getFromNotLocalizableAsset(string $assetCode, string $channelCode): array;

    /**
     * Available since Akeneo PIM 2.1.
     * Uploads a new variation file for a given localizable asset, channel and locale.
     *
     * @param string|resource $variationFile file path or resource of the variation file to upload
     * @param string          $assetCode     code of the asset
     * @param string          $channelCode   code of the channel
     * @param string          $localeCode    code of the locale
     *
     * @return int Status code 201 indicating that the asset variation file has been well uploaded
     */
    public function uploadForLocalizableAsset($variationFile, string $assetCode, string $channelCode, string $localeCode): int;

    /**
     * Available since Akeneo PIM 2.1.
     * Uploads a new variation file for a given not localizable asset and channel.
     *
     * @param string|resource $variationFile file path or resource of the variation file to upload
     * @param string          $assetCode     code of the asset
     * @param string          $channelCode   code of the channel
     *
     * @return int Status code 201 indicating that the asset variation file has been well uploaded
     */
    public function uploadForNotLocalizableAsset($variationFile, string $assetCode, string $channelCode): int;

    /**
     * Available since Akeneo PIM 2.1.
     * Downloads an asset variation file by its asset code, channel code and local code for a localizable asset.
     *
     * @param string $assetCode   code of the asset
     * @param string $channelCode code of the channel
     * @param string $localeCode  code of the locale
     *
     * @return ResponseInterface
     */
    public function downloadFromLocalizableAsset(string $assetCode, string $channelCode, string $localeCode): ResponseInterface;

    /**
     * Available since Akeneo PIM 2.1.
     * Downloads an asset variation file by its asset code and channel code for a not localizable asset.
     *
     * @param string $assetCode   code of the asset
     * @param string $channelCode code of the channel
     *
     * @return ResponseInterface
     */
    public function downloadFromNotLocalizableAsset(string $assetCode, string $channelCode): ResponseInterface;
}
