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
    const PRODUCT_MODEL_DRAFT_URI = '/api/rest/v1/product-models/%s/draft';
    const PRODUCT_MODEL_PROPOSAL_URI = '/api/rest/v1/product-models/%s/proposal';

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
