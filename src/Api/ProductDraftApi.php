<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;

/**
 * API implementation to manage product drafts.
 *
 * @author    Damien Carcel <damien.carcel@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductDraftApi implements ProductDraftApiInterface
{
    public const PRODUCT_DRAFT_URI = '/api/rest/v1/products/%s/draft';
    public const PRODUCT_PROPOSAL_URI = '/api/rest/v1/products/%s/proposal';

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
        return $this->resourceClient->getResource(static::PRODUCT_DRAFT_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForApproval($code)
    {
        return $this->resourceClient->createResource(static::PRODUCT_PROPOSAL_URI, [$code]);
    }
}
