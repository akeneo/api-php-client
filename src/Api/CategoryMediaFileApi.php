<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * API implementation to manage enriched categories media files.
 *
 * @copyright 2023 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryMediaFileApi implements DownloadableResourceInterface
{
    public const MEDIA_FILE_DOWNLOAD_URI = 'api/rest/v1/category-media-files/%s/download';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $code): ResponseInterface
    {
        return $this->resourceClient->getStreamedResource(static::MEDIA_FILE_DOWNLOAD_URI, [$code]);
    }
}
