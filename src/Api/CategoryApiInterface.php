<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Exception\HttpException;
use Akeneo\Pim\Pagination\Page;
use Akeneo\Pim\Pagination\PageInterface;

/**
 * API to manage the categories.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CategoryApiInterface
{
    /**
     * Gets a list of categories by returning the first page.
     * Consequently, this method does not return all the categories.
     *
     * @param int   $limit           The maximum number of categories to return.
     *                               Do note that the server has a maximum limit allowed.
     * @param bool  $withCount       Set to true to return the total count of categories.
     *                               This parameter could decrease drastically the performance when set to true.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException
     *
     * @return PageInterface
     */
    public function getCategories($limit = 10, $withCount = false, array $queryParameters = []);

    /**
     * Gets a cursor to iterate over a list of categories.
     *
     * @param int   $pageSize        The size of the page returned by the server.
     *                               Do note that the server has a maximum limit allowed.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @throws HttpException
     *
     * @return PageInterface
     */
    public function getCategoryCursor($pageSize = 10, array $queryParameters = []);

    /**
     * Creates a category.
     *
     * @param string $code code of the category to create
     * @param array  $data data of the category to create
     *
     * @throws HttpException
     */
    public function createCategory($code, array $data = []);

    /**
     * Creates a category if the category does not exist yet, otherwise updates partially the category.
     *
     * @param string $code code of the category to create or update
     * @param array  $data data of the category to create or update
     *
     * @throws HttpException
     *
     * @return int returns either http code 201 if the category has been created or 204 if it has been updated
     */
    public function partialUpdateCategory($code, array $data = []);
}
