<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;

/**
 * API implementation to manage product model drafts.
 *
 * @author    Elodie Raposo <elodie.raposo@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductModelDraftApi implements ProductModelDraftApiInterface
{
    public const PRODUCT_MODEL_DRAFT_URI = '/api/rest/v1/product-models/%s/draft';
    public const PRODUCT_MODEL_PROPOSAL_URI = '/api/rest/v1/product-models/%s/proposal';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $code): array
    {
        return $this->resourceClient->getResource(static::PRODUCT_MODEL_DRAFT_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForApproval($code)
    {
        return $this->resourceClient->createResource(static::PRODUCT_MODEL_PROPOSAL_URI, [$code]);
    }
}
