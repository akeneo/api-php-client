<?php

namespace Akeneo\Pim\ApiClient\Api\Operation;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * API that can fetch a list of resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ListableResourceInterface
{
    /**
     * Gets a list of resources by returning the first page.
     * Consequently, this method does not return all the resources.
     *
     * @param int   $limit           The maximum number of resources to return.
     *                               Do note that the server has a maximum limit allowed.
     * @param bool  $withCount       Set to true to return the total count of resources.
     *                               This parameter could decrease drastically the performance when set to true.
     * @param array $queryParameters Additional query parameters to pass in the request.
     *
     * @throws HttpException If the request failed.
     *
     * @return PageInterface
     */
    public function listPerPage(int $limit = 10, bool $withCount = false, array $queryParameters = []): PageInterface;

    /**
     * Gets a cursor to iterate over a list of resources.
     *
     * @param int   $pageSize        The size of the page returned by the server.
     *                               Do note that the server has a maximum limit allowed.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException If the request failed.
     *
     * @return ResourceCursorInterface
     */
    public function all(int $pageSize = 10, array $queryParameters = []): ResourceCursorInterface;
}
