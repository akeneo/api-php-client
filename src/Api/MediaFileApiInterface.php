<?php

namespace Akeneo\Pim\Api;

/**
 * API to manage the media files.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface MediaFileApiInterface extends ListableResourceInterface, GettableResourceInterface
{
    /**
     * Creates a new media file and associates it to a resource.
     *
     * @param string|resource $mediaFile File path or resource of the media file
     * @param array           $data      The data of the resource to which the media file will be associated
     *
     * @throws HttpException
     * @throws \RuntimeException if the file could not be opened
     *
     * @return int returns 201 if the media file has been created
     */
    public function create($mediaFile, array $data);
}
