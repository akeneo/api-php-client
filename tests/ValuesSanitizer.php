<?php

namespace Akeneo\Pim\tests;

/**
 * Sanitizes a collection of normalized values.
 *
 * @author    Damien Carcel <damien.carcel@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ValuesSanitizer
{
    /**
     * @param mixed $values
     *
     * @return mixed
     */
    public static function sanitize($values)
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $values[$key] = static::sanitize($value);
            } else {
                $values[$key] = DateSanitizer::sanitize($values[$key]);
                $values[$key] = MediaSanitizer::sanitize($values[$key]);
            }
        }

        return $values;
    }
}
