<?php

namespace Akeneo\Pim\ApiClient\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Aims to get the next line of a stream.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class LineStreamReader
{
    /**
     * Gets the next line of a stream.
     *
     * @param StreamInterface $stream
     *
     * @return string|null returns the line, or null if the stream is not readable or at the end
     */
    public function getNextLine(StreamInterface $stream): ?string
    {
        if (!$stream->isReadable() || $stream->eof()) {
            return null;
        }

        $line  = '';
        $isEol = false;
        while (!$stream->eof() && !$isEol) {
            $character = $stream->read(1);
            $isEol = PHP_EOL === $character;

            if (!$isEol) {
                $line .= $character;
            }
        }

        return $line;
    }
}
