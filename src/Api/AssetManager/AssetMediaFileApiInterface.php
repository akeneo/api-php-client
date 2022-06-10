<?php
declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AssetManager;

use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;

interface AssetMediaFileApiInterface extends DownloadableResourceInterface
{

    /**
     * Creates a new asset media file.
     *
     * @param string|resource $mediaFile File path or resource of the media file
     *
     * @throws HttpException    If the request failed.
     * @throws RuntimeException If the file could not be opened.
     *
     * @return string returns the code of the created media file
     */
    public function create($mediaFile): string;
}
