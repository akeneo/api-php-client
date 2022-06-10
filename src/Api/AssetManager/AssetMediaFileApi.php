<?php
declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Psr\Http\Message\ResponseInterface;

class AssetMediaFileApi implements AssetMediaFileApiInterface
{
    const MEDIA_FILE_DOWNLOAD_URI = 'api/rest/v1/asset-media-files/%s';
    const MEDIA_FILE_CREATE_URI = 'api/rest/v1/asset-media-files';

    /** @var ResourceClientInterface */
    private $resourceClient;

    /** @var FileSystemInterface */
    private $fileSystem;

    public function __construct(ResourceClientInterface $resourceClient, FileSystemInterface $fileSystem)
    {
        $this->resourceClient = $resourceClient;
        $this->fileSystem = $fileSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $code): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource(static::MEDIA_FILE_DOWNLOAD_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function create($mediaFile): string
    {
        if (is_string($mediaFile)) {
            $mediaFile = $this->fileSystem->getResourceFromPath($mediaFile);
        }

        $requestParts = [
            [
                'name' => 'file',
                'contents' => $mediaFile,
            ]
        ];

        $response = $this->resourceClient->createMultipartResource(static::MEDIA_FILE_CREATE_URI, [], $requestParts);

        return $this->extractCodeFromCreationResponse($response);
    }

    /**
     * Extracts the code of a media-file from a creation response.
     *
     * @param ResponseInterface $response
     *
     * @throws RuntimeException if unable to extract the code
     *
     * @return string
     */
    private function extractCodeFromCreationResponse(ResponseInterface $response): string
    {
        $header = $response->getHeader('asset-media-file-code');

        if (empty($header)) {
            throw new RuntimeException('The response does not contain the code of the created media-file.');
        }

        return (string) $header[0];
    }
}
