<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Pagination\Page;

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
     *                               Do note that the server has a default value if you don't specify anything.
     *                               The server has a maximum limit allowed as well.
     * @param bool  $withCount       Set to true to return the total count of categories.
     *                               This parameter could decrease drastically the performance when set to true.
     * @param array $queryParameters Additional query parameters to pass in the request
     *
     * @return Page
     */
    public function getCategories($limit = 10, $withCount = false, array $queryParameters = []);
}
