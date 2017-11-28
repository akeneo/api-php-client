<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\CreatableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;

/**
 * API to manage the categories.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CategoryApiInterface extends
    GettableResourceInterface,
    ListableResourceInterface,
    CreatableResourceInterface,
    UpsertableResourceInterface,
    UpsertableResourceListInterface
{
}
