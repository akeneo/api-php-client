<?php

namespace Akeneo\Pim\ApiClient\FileSystem;

use Akeneo\Pim\ApiClient\Exception\UnreadableFileException;

/**
 * File system to manipulate files stored locally.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class LocalFileSystem implements FileSystemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getResourceFromPath(string $filePath)
    {
        if (!is_readable($filePath)) {
            throw new UnreadableFileException(sprintf('The file "%s" could not be read.', $filePath));
        }

        $fileResource = fopen($filePath, 'rb');

        if (!is_resource($fileResource)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be opened.', $filePath));
        }

        return $fileResource;
    }
}
