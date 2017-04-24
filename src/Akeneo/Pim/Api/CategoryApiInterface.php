<?php

namespace Akeneo\Pim\Api;

/**
 * Interface to interact with categories from the API
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CategoryApiInterface
{
    /**
     * Get a list of categories
     *
     * @param int   $limit                     The maximum number of categories returned per response
     * @param bool  $withCount                 Set to true to return the count of products in the response
     * @param array $additionalQueryParameters Additional query parameters
     *
     * @return mixed
     */
    public function getCategories($limit = null, $withCount = null, array $additionalQueryParameters = []);
}
