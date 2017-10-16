<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Exception\HttpException;

/**
 * Manages Family Variants
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface FamilyVariantApiInterface
{
    /**
     * Available from Akeneo PIM 2.0.
     * Gets a family variant by its code.
     *
     * @param string $familyCode        Code of the parent family
     * @param string $familyVariantCode Code of the family variant
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get($familyCode, $familyVariantCode);
}
