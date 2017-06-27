<?php

namespace Akeneo\Pim\tests;

/**
 * Sanitize a date.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DateSanitizer
{
    const DATE_FIELD_COMPARISON = 'this is a date formatted to ISO-8601';
    const DATE_FIELD_PATTERN = '#[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}\+[0-9]{2}:[0-9]{2}$#';

    /**
     * Replaces date by self::DATE_FIELD_COMPARISON.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public static function sanitize($data)
    {
        if (1 === preg_match(self::DATE_FIELD_PATTERN, $data)) {
            return self::DATE_FIELD_COMPARISON;
        }

        return $data;
    }
}
