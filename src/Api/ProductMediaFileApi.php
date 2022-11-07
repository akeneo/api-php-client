<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * API implementation to manage the media files for the products.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductMediaFileApi implements MediaFileApiInterface
{
    public const MEDIA_FILES_URI = 'api/rest/v1/media-files';
    public const MEDIA_FILE_URI = 'api/rest/v1/media-files/%s';
    public const MEDIA_FILE_DOWNLOAD_URI = 'api/rest/v1/media-files/%s/download';
    public const MEDIA_FILE_URI_CODE_REGEX = '~/api/rest/v1/media\-files/(?P<code>.*)$~';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory,
        private FileSystemInterface $fileSystem
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $code): array
    {
        return $this->resourceClient->getResource(static::MEDIA_FILE_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function listPerPage(int $limit = 100, bool $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(static::MEDIA_FILES_URI, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function all(int $pageSize = 100, array $queryParameters = []): ResourceCursorInterface
    {
        $firstPage = $this->listPerPage($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create($mediaFile, array $data): string
    {
        if (is_string($mediaFile)) {
            $mediaFile = $this->fileSystem->getResourceFromPath($mediaFile);
        }

        $name = isset($data['type']) ? $data['type'] : 'product';
        unset($data['type']);
        $requestParts = [
            [
                'name' => $name,
                'contents' => json_encode($data),
            ],
            [
                'name' => 'file',
                'contents' => $mediaFile,
            ]
        ];

        $response = $this->resourceClient->createMultipartResource(static::MEDIA_FILES_URI, [], $requestParts);

        return $this->extractCodeFromCreationResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $code): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource(static::MEDIA_FILE_DOWNLOAD_URI, [$code]);
    }

    /**
     * Extracts the code of a media-file from a creation response.
     *
     * @throws RuntimeException if unable to extract the code
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    protected function extractCodeFromCreationResponse(ResponseInterface $response)
    {
        if (!$response->hasHeader('location')) {
            throw new RuntimeException('The response does not contain the URI of the created media-file.');
        }
        $locationHeader = $response->getHeader('location')[0];

        $matches = [];
        if (1 !== preg_match(static::MEDIA_FILE_URI_CODE_REGEX, $locationHeader, $matches)) {
            throw new RuntimeException('Unable to find the code in the URI of the created media-file.');
        }

        return $matches['code'];
    }
}
