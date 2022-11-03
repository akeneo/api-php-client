<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api\AppCatalog;

use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface AppCatalogProductApiInterface
{
    public function all(string $catalogId, int $limit = 100, array $queryParameters = []): ResourceCursorInterface;
}
