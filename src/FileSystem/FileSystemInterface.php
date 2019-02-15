<?php

namespace Akeneo\Pim\ApiClient\FileSystem;

use Akeneo\Pim\ApiClient\Exception\UnreadableFileException;

/**
 * Manipulates files for the API.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface FileSystemInterface
{
    /**
     * Gets the resource of a file from its path.
     *
     * @param string $filePath Path of the file
     *
     * @throws UnreadableFileException if the file doesn't exists or is not readable
     *
     * @return resource
     */
    public function getResourceFromPath(string $filePath);
}
