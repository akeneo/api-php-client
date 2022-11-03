<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Api\Operation\DeletableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface AppCatalogApiInterface extends
    ListableResourceInterface,
    GettableResourceInterface,
    DeletableResourceInterface
{
    public function create(array $data): array;

    public function upsert(string $code, array $data = []): array;
}
