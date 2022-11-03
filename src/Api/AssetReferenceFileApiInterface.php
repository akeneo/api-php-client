<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\UploadAssetReferenceFileErrorException;
use Psr\Http\Message\ResponseInterface;

/**
 * API to manage asset reference files.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @deprecated Route unavailable in latest PIM versions. Class will be removed in v12.0.0.
 * @see \Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApiInterface instead.
 */
interface AssetReferenceFileApiInterface
{
    /**
     * Available since Akeneo PIM 2.1.
     * Gets an asset reference file by its asset code and local code for a localizable asset.
     *
     * @param string $assetCode  code of the asset
     * @param string $localeCode code of the locale
     *
     * @throws HttpException If the request failed
     *
     * @return array
     */
    public function getFromLocalizableAsset(string $assetCode, string $localeCode): array;

    /**
     * Available since Akeneo PIM 2.1.
     * Gets an asset reference file by its asset code for a not localizable asset.
     *
     * @param string $assetCode code of the asset
     *
     * @throws HttpException If the request failed
     *
     * @return array
     */
    public function getFromNotLocalizableAsset(string $assetCode): array;

    /**
     * Available since Akeneo PIM 2.1.
     * Uploads a new reference file for a given localizable asset and locale.
     * It will also automatically generate all the variation files corresponding to this reference file.
     *
     * @param string|resource $referenceFile file path or resource of the reference file to upload
     * @param string          $assetCode     code of the asset
     * @param string          $localeCode    code of the locale
     *
     * @throws HttpException If the request failed
     * @throws UploadAssetReferenceFileErrorException If the upload returned any errors
     *
     * @return int Status code 201 indicating that the asset reference file has been well uploaded
     */
    public function uploadForLocalizableAsset($referenceFile, string $assetCode, string $localeCode): int;

    /**
     * Available since Akeneo PIM 2.1.
     * Uploads a new reference file for a given not localizable asset.
     * It will also automatically generate all the variation files corresponding to this reference file.
     *
     * @param string|resource $referenceFile file path or resource of the reference file to upload
     * @param string          $assetCode     code of the asset
     *
     * @throws HttpException If the request failed
     * @throws UploadAssetReferenceFileErrorException If the upload returned any errors
     *
     * @return int Status code 201 indicating that the asset reference file has been well uploaded
     */
    public function uploadForNotLocalizableAsset($referenceFile, string $assetCode): int;

    /**
     * Available since Akeneo PIM 2.1.
     * Download an asset reference file by its asset code and local code for a localizable asset.
     *
     * @param string $assetCode  code of the asset
     * @param string $localeCode code of the locale
     *
     * @throws HttpException If the request failed
     *
     * @return ResponseInterface
     */
    public function downloadFromLocalizableAsset(string $assetCode, string $localeCode): ResponseInterface;

    /**
     * Available since Akeneo PIM 2.1.
     * Download an asset reference file by its asset code for a not localizable asset.
     *
     * @param string $assetCode code of the asset
     *
     * @throws HttpException If the request failed
     *
     * @return ResponseInterface
     */
    public function downloadFromNotLocalizableAsset(string $assetCode): ResponseInterface;
}
