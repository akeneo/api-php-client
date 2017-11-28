<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * This class contains the list of parameters to use for the pagination of the API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class PaginationParameter
{
    const SEARCH = 'search';
    const LIMIT = 'limit';
    const WITH_COUNT = 'with_count';
}
