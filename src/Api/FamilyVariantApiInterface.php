<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Exception\HttpException;
use Akeneo\Pim\Exception\InvalidArgumentException;

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

    /**
     * Available from Akeneo PIM 2.0.
     * Creates a family variant.
     *
     * @param string $familyCode        code of the family parent of the family variant to create
     * @param string $familyVariantCode code of the family variant to create
     * @param array  $data              data of the family variant to create
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the parameter "familyCode" is defined in the data parameter.
     *
     * @return int Status code 201 indicating that the family variant has been well created.
     */
    public function create($familyCode, $familyVariantCode, array $data = []);
}
