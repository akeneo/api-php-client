<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class ProductDraftUuidApi implements ProductDraftUuidApiInterface
{
    const PRODUCT_DRAFT_UUID_URI = '/api/rest/v1/products-uuid/%s/draft';
    const PRODUCT_PROPOSAL_UUID_URI = '/api/rest/v1/products-uuid/%s/proposal';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /** @var PageFactoryInterface */
    protected $pageFactory;

    /** @var ResourceCursorFactoryInterface */
    protected $cursorFactory;

    public function __construct(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->resourceClient = $resourceClient;
        $this->pageFactory = $pageFactory;
        $this->cursorFactory = $cursorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $uuid): array
    {
        return $this->resourceClient->getResource(static::PRODUCT_DRAFT_UUID_URI, [$uuid]);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForApproval(string $uuid): int
    {
        return $this->resourceClient->createResource(static::PRODUCT_PROPOSAL_UUID_URI, [$uuid]);
    }
}
