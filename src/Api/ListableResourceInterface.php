<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Exception\HttpException;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;

/**
 * API that can fetch a list of resources
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
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException
     *
     * @return PageInterface
     */
    public function listPerPage($limit = 10, $withCount = false, array $queryParameters = []);

    /**
     * Gets a cursor to iterate over a list of resources.
     *
     * @param int   $pageSize        The size of the page returned by the server.
     *                               Do note that the server has a maximum limit allowed.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException
     *
     * @return ResourceCursorInterface
     */
    public function all($pageSize = 10, array $queryParameters = []);
}
