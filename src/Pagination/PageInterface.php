<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * Page interface represents a list of paginated resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface PageInterface
{
    /**
     * Returns the first page of the list of resources.
     *
     * @return PageInterface
     */
    public function getFirstPage();

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     *
     * @return PageInterface|null
     */
    public function getPreviousPage();

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     *
     * @return PageInterface|null
     */
    public function getNextPage();

    /**
     * Gets the total count of resources, not just the number of items in the page.
     * It returns null if the option to process it has not been send in the request.
     *
     * @return int|null
     */
    public function getCount();

    /**
     * Returns the array of resources in the page.
     *
     * @return array
     */
    public function getItems();

    /**
     * Returns true if a next page exists, false either.
     *
     * @return bool
     */
    public function hasNextPage();

    /**
     * Returns true if a previous page exists, false either.
     *
     * @return bool
     */
    public function hasPreviousPage();

    /**
     * Gets the link of the next page.
     * Returns null if there is not next page.
     *
     * @return string|null
     */
    public function getNextLink();

    /**
     * Gets the link of the previous page.
     * Returns null if there is not previous page.
     *
     * @return string|null
     */
    public function getPreviousLink();
}
