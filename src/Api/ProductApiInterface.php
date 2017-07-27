<?php

namespace Akeneo\Pim\Api;

/**
 * API to manage the products.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ProductApiInterface extends
    ListableResourceInterface,
    GettableResourceInterface,
    CreatableResourceInterface,
    UpsertableResourceInterface,
    UpsertableResourceListInterface,
    DeletableResourceInterface
{
}
