<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceEntityMediaFileApi implements ReferenceEntityMediaFileApiInterface
{
    const MEDIA_FILE_DOWNLOAD_URI = 'api/rest/v1/reference-entities-media-files/%s';
    const MEDIA_FILE_CREATE_URI = 'api/rest/v1/reference-entities-media-files';

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
    public function download($code): ResponseInterface
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
        $header = $response->getHeader('Reference-entities-media-file-code');

        if (empty($header)) {
            throw new RuntimeException('The response does not contain the code of the created media-file.');
        }

        return (string)$header[0];
    }
}
