<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * API to manage the attribute options.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AttributeOptionApiInterface
{
    /**
     * Gets a single attribute option.
     *
     * @param string $attributeCode Code of the attribute
     * @param string $code          Code of the attribute option
     *
     * @throws HttpException If the request failed.
     *
     * @return array
     */
    public function get($attributeCode, $code): array;

    /**
     * Gets a list of attribute options by returning the first page.
     * Consequently, this method does not return all the attribute options.
     *
     * @param string $attributeCode   Code of the attribute
     * @param int    $limit           The maximum number of attribute options to return.
     *                                Do note that the server has a maximum limit allowed.
     * @param bool   $withCount       Set to true to return the total count of attribute options.
     *                                This parameter could decrease drastically the performance when set to true.
     * @param array  $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return PageInterface
     */
    public function listPerPage($attributeCode, $limit = 10, $withCount = false, array $queryParameters = []): PageInterface;

    /**
     * Gets a cursor to iterate over a list of attribute options.
     *
     * @param string $attributeCode  Code of the attribute
     * @param int   $pageSize        The size of the page returned by the server.
     *                               Do note that the server has a maximum limit allowed.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResourceCursorInterface
     */
    public function all($attributeCode, $pageSize = 10, array $queryParameters = []): ResourceCursorInterface;

    /**
     * Creates an attribute option.
     *
     * @param string $attributeCode       code of the attribute
     * @param string $attributeOptionCode code of the attribute option to create
     * @param array  $data                data of the attribute option to create
     *
     * @throws HttpException            If the request failed.
     * @throws InvalidArgumentException If the parameter "code" or "attribute" is defined in the data parameter.
     *
     * @return int returns 201 if the attribute option has been created
     */
    public function create($attributeCode, $attributeOptionCode, array $data = []): int;

    /**
     * Creates an attribute option if it does not exist yet, otherwise updates partially the attribute option.
     *
     * @param string $attributeCode       code of the attribute
     * @param string $attributeOptionCode code of the attribute option to create or update
     * @param array  $data                data of the attribute option to create or update
     *
     * @throws HttpException
     *
     * @return int returns either http code 201 if the attribute option has been created or 204 if it has been updated
     */
    public function upsert($attributeCode, $attributeOptionCode, array $data = []): int;

    /**
     * Updates or creates several attribute options at once.
     *
     * @param string                $attributeCode    code of the attribute
     * @param array|StreamInterface $attributeOptions array or StreamInterface object containing data of the attribute options to create or update
     *
     * @throws HttpException
     *
     * @return \Traversable returns an iterable object, each entry corresponding to the response of the upserted attribute options
     */
    public function upsertList($attributeCode, $attributeOptions): \Traversable;
}
