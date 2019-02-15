<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * Factory interface to create a resource cursor object to iterate over a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ResourceCursorFactoryInterface
{
    /**
     * Creates a cursor from the first page of resources.
     */
    public function createCursor(?int $pageSize, PageInterface $firstPage): ResourceCursorInterface;
}
