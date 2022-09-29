<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
interface ProductDraftUuidApiInterface extends GettableResourceInterface
{
    /**
     * Submits a product draft for approval, by its uuid.
     */
    public function submitForApproval(string $uuid): int;
}
