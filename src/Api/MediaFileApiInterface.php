<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;

/**
 * API to manage the media files.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface MediaFileApiInterface extends
    ListableResourceInterface,
    GettableResourceInterface,
    DownloadableResourceInterface
{
    /**
     * Creates a new media file and associates it to a resource.
     *
     * @param string|resource $mediaFile File path or resource of the media file
     * @param array           $data      The data of the resource to which the media file will be associated
     *
     * @throws HttpException    If the request failed.
     * @throws RuntimeException If the file could not be opened.
     *
     * @return string returns the code of created media file
     */
    public function create($mediaFile, array $data): string;
}
