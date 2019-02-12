<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceCursorFactory implements ResourceCursorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCursor(?int $pageSize, PageInterface $firstPage): ResourceCursorInterface
    {
        return new ResourceCursor($pageSize, $firstPage);
    }
}
