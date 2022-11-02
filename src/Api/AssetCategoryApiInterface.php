<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\CreatableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;

/**
 * API to manage asset categories
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @deprecated Route unavailable in latest PIM versions. Class will be removed in v12.0.0.
 * @see \Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApiInterface instead.
 */
interface AssetCategoryApiInterface extends
    GettableResourceInterface,
    ListableResourceInterface,
    UpsertableResourceInterface,
    UpsertableResourceListInterface,
    CreatableResourceInterface
{
}
