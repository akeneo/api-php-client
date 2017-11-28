<?php

namespace Akeneo\Pim\ApiClient\tests;

/**
 * Sanitize a media.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaSanitizer
{
    const MEDIA_ATTRIBUTE_DATA_COMPARISON = 'this is a media identifier';
    const MEDIA_ATTRIBUTE_DATA_PATTERN = '#([0-9a-z]/){4}[0-9a-z]{40}_\w+\.[a-zA-Z]+(/download)?$#';

    /**
     * Replaces media attributes data in the $data by self::MEDIA_ATTRIBUTE_DATA_COMPARISON.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public static function sanitize($data)
    {
        if (1 === preg_match(self::MEDIA_ATTRIBUTE_DATA_PATTERN, $data)) {
            return self::MEDIA_ATTRIBUTE_DATA_COMPARISON;
        }

        return $data;
    }
}
