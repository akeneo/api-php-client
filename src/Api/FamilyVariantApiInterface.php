<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Psr\Http\Message\StreamInterface;

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
     * Available since Akeneo PIM 2.0.
     * Gets a family variant by its code.
     *
     * @param string $familyCode        Code of the parent family
     * @param string $familyVariantCode Code of the family variant
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get($familyCode, $familyVariantCode): array;

    /**
     * Available since Akeneo PIM 2.0.
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
    public function create($familyCode, $familyVariantCode, array $data = []): int;

    /**
     * Available since Akeneo PIM 2.0.
     * Creates a family variant if it does not exist yet, otherwise updates it partially.
     *
     * @param string $familyCode        code of the family parent of the family variant to create or update
     * @param string $familyVariantCode code of the family variant to create or update
     * @param array  $data              data of the family variant to create or update
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the parameter "familyCode" is defined in the data parameter.
     *
     * @return int Status code 201 indicating that the family variant has been well created.
     *             Status code 204 indicating that the family variant has been well updated.
     */
    public function upsert($familyCode, $familyVariantCode, array $data = []): int;

    /**
     * Available since Akeneo PIM 2.0.
     * Gets a list of family variants by returning the first page.
     * Consequently, this method does not return all the family variants.
     *
     * @param string $familyCode      Family code from which you want to get a list of family variants.
     * @param int    $limit           The maximum number of family variants to return.
     *                                Do note that the server has a maximum limit allowed.
     * @param bool   $withCount       Set to true to return the total count of family variants.
     *                                This parameter could decrease drastically the performance when set to true.
     * @param array  $queryParameters Additional query parameters to pass in the request.
     *
     * @throws HttpException If the request failed.
     *
     * @return PageInterface
     */
    public function listPerPage($familyCode, $limit = 10, $withCount = false, array $queryParameters = []): PageInterface;

    /**
     * Available since Akeneo PIM 2.0.
     * Gets a cursor to iterate over a list of family variants.
     *
     * @param string $familyCode      Family code from which you want to get a list of family variants.
     * @param int    $pageSize        The size of the page returned by the server.
     *                                Do note that the server has a maximum limit allowed.
     * @param array  $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResourceCursorInterface
     */
    public function all($familyCode, $pageSize = 10, array $queryParameters = []): ResourceCursorInterface;

    /**
     * Available since Akeneo PIM 2.0.
     * Updates or creates several resources.
     *
     * @param string                $familyCode code of the family parent of family variants to create or update
     * @param array|StreamInterface $familyVariants array or StreamInterface object containing the family variants to create or update
     *
     * @throws HttpException
     *
     * @return \Traversable returns an iterable object, each entry corresponding to the response of the upserted resource
     */
    public function upsertList($familyCode, $familyVariants): \Traversable;
}
