<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * Factory interface to create a page object representing a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface PageFactoryInterface
{
    /**
     * Creates a page object from body.
     */
    public function createPage(array $data): PageInterface;
}
