<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;

/**
 * API to manage product model drafts.
 *
 * @author    Elodie Raposo <elodie.raposo@gmail.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ProductModelDraftApiInterface extends GettableResourceInterface
{
    /**
     * Submits a product model draft for approval, by its code.
     *
     * @param string $code
     *
     * @return int
     */
    public function submitForApproval($code);
}
