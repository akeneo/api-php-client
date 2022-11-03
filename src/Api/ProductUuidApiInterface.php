<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface ProductUuidApiInterface extends ListableResourceInterface, UpsertableResourceListInterface
{
    public function get(string $uuid, array $queryParameters = []): array;

    public function create(string $uuid, array $data = []): int;

    public function upsert(string $uuid, array $data = []): int;

    public function delete(string $uuid): int;
}
