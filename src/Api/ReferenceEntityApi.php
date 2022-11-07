<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @author    Tamara Robichet <tamara.robichet@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceEntityApi implements ReferenceEntityApiInterface
{
    public const REFERENCE_ENTITY_URI = 'api/rest/v1/reference-entities/%s';
    public const REFERENCE_ENTITIES_URI = 'api/rest/v1/reference-entities';

    public function __construct(
        private ResourceClientInterface $resourceClient,
        private PageFactoryInterface $pageFactory,
        private ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $referenceEntityCode): array
    {
        return $this->resourceClient->getResource(static::REFERENCE_ENTITY_URI, [$referenceEntityCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::REFERENCE_ENTITIES_URI,
            [],
            null,
            false,
            $queryParameters
        );

        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor(null, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $referenceEntityCode, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::REFERENCE_ENTITY_URI, [$referenceEntityCode], $data);
    }
}
