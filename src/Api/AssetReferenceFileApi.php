<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\UploadAssetReferenceFileErrorException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * API implementation to manage asset reference files.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @deprecated Route unavailable in latest PIM versions. Class will be removed in v12.0.0.
 * @see \Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApi instead.
 */
class AssetReferenceFileApi implements AssetReferenceFileApiInterface
{
    public const ASSET_REFERENCE_FILE_URI = '/api/rest/v1/assets/%s/reference-files/%s';
    public const ASSET_REFERENCE_FILE_DOWNLOAD_URI = '/api/rest/v1/assets/%s/reference-files/%s/download';
    public const NOT_LOCALIZABLE_ASSET_LOCALE_CODE = 'no-locale';

    public function __construct(
        private ResourceClientInterface $resourceClient,
        private FileSystemInterface $fileSystem
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFromLocalizableAsset(string $assetCode, string $localeCode): array
    {
        return $this->get($assetCode, $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getFromNotLocalizableAsset(string $assetCode): array
    {
        return $this->get($assetCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadForLocalizableAsset($referenceFile, string $assetCode, string $localeCode): int
    {
        return $this->upload($referenceFile, $assetCode, $localeCode);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadForNotLocalizableAsset($referenceFile, string $assetCode): int
    {
        return $this->upload($referenceFile, $assetCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFromLocalizableAsset(string $assetCode, string $localeCode): ResponseInterface
    {
        return $this->resourceClient
            ->getStreamedResource(static::ASSET_REFERENCE_FILE_DOWNLOAD_URI, [$assetCode, $localeCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFromNotLocalizableAsset(string $assetCode): ResponseInterface
    {
        return $this->resourceClient
            ->getStreamedResource(static::ASSET_REFERENCE_FILE_DOWNLOAD_URI, [$assetCode, static::NOT_LOCALIZABLE_ASSET_LOCALE_CODE]);
    }

    /**
     * @param string|resource $referenceFile
     */
    private function upload($referenceFile, string $assetCode, string $localeCode): int
    {
        if (is_string($referenceFile)) {
            $referenceFile = $this->fileSystem->getResourceFromPath($referenceFile);
        }

        $requestParts = [[
            'name' => 'file',
            'contents' => $referenceFile,
        ]];

        $response = $this->resourceClient->createMultipartResource(
            static::ASSET_REFERENCE_FILE_URI,
            [$assetCode, $localeCode],
            $requestParts
        );
        $this->handleUploadErrors($response);

        return $response->getStatusCode();
    }

    /**
     * @throws UploadAssetReferenceFileErrorException if an upload returns any errors.
     */
    private function handleUploadErrors(ResponseInterface $response): void
    {
        $decodedResponse = json_decode($response->getBody()->getContents(), true);
        $errors = isset($decodedResponse['errors']) ? $decodedResponse['errors'] : null;

        if (is_array($errors) && !empty($errors)) {
            $message = isset($decodedResponse['message']) ? $decodedResponse['message'] : 'Errors occurred during the upload.';

            throw new UploadAssetReferenceFileErrorException($message, $errors);
        }
    }

    private function get(string $assetCode, string $localeCode): array
    {
        return $this->resourceClient->getResource(static::ASSET_REFERENCE_FILE_URI, [$assetCode, $localeCode]);
    }
}
