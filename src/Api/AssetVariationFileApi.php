<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AssetVariationFileApi
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @deprecated Route unavailable in latest PIM versions. Class will be removed in v12.0.0.
 */
class AssetVariationFileApi implements AssetVariationFileApiInterface
{
    public const ASSET_VARIATION_FILE_URI = '/api/rest/v1/assets/%s/variation-files/%s/%s';
    public const ASSET_VARIATION_FILE_DOWNLOAD_URI = '/api/rest/v1/assets/%s/variation-files/%s/%s/download';
    public const NOT_LOCALIZABLE_ASSET_LOCALE_CODE = 'no-locale';

    public function __construct(
        private ResourceClientInterface $resourceClient,
        private FileSystemInterface $fileSystem
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFromNotLocalizableAsset(string $assetCode, string $channelCode): array
    {
        return $this->get($assetCode, $channelCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function getFromLocalizableAsset(string $assetCode, string $channelCode, string $localeCode): array
    {
        return $this->get($assetCode, $channelCode, $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadForNotLocalizableAsset($variationFile, string $assetCode, string $channelCode): int
    {
        return $this->upload($variationFile, $assetCode, $channelCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadForLocalizableAsset($variationFile, string $assetCode, string $channelCode, string $localeCode): int
    {
        return $this->upload($variationFile, $assetCode, $channelCode, $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFromLocalizableAsset(string $assetCode, string $channelCode, string $localeCode): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource(
            static::ASSET_VARIATION_FILE_DOWNLOAD_URI,
            [$assetCode, $channelCode, $localeCode]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFromNotLocalizableAsset(string $assetCode, string $channelCode): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource(
            static::ASSET_VARIATION_FILE_DOWNLOAD_URI,
            [$assetCode, $channelCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE]
        );
    }

    private function get(string $assetCode, string $channelCode, string $localeCode): array
    {
        return $this->resourceClient->getResource(static::ASSET_VARIATION_FILE_URI, [$assetCode, $channelCode, $localeCode]);
    }

    /**
     * @param string|resource $variationFile
     */
    private function upload($variationFile, string $assetCode, string $channelCode, string $localeCode): int
    {
        if (is_string($variationFile)) {
            $variationFile = $this->fileSystem->getResourceFromPath($variationFile);
        }

        $requestParts = [[
            'name' => 'file',
            'contents' => $variationFile,
        ]];

        $response = $this->resourceClient->createMultipartResource(
            static::ASSET_VARIATION_FILE_URI,
            [$assetCode, $channelCode, $localeCode],
            $requestParts
        );

        return $response->getStatusCode();
    }
}
