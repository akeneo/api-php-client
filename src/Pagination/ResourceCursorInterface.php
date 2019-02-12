<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * Cursor interface  iterate over a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ResourceCursorInterface extends \Iterator
{
    /**
     * Get the number of resources per page.
     */
    public function getPageSize(): ?int;
}
