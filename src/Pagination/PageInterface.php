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
     */
    public function getFirstPage(): PageInterface;

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     */
    public function getPreviousPage(): ?PageInterface;

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     */
    public function getNextPage(): ?PageInterface;

    /**
     * Gets the total count of resources, not just the number of items in the page.
     * It returns null if the option to process it has not been send in the request.
     */
    public function getCount(): ?int;

    /**
     * Returns the array of resources in the page.
     */
    public function getItems(): array;

    /**
     * Returns true if a next page exists, false either.
     */
    public function hasNextPage(): bool;

    /**
     * Returns true if a previous page exists, false either.
     */
    public function hasPreviousPage(): bool;

    /**
     * Gets the link of the next page.
     * Returns null if there is not next page.
     */
    public function getNextLink(): ?string;

    /**
     * Gets the link of the previous page.
     * Returns null if there is not previous page.
     */
    public function getPreviousLink(): ?string;
}
