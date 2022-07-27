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
    const REFERENCE_ENTITY_URI = 'api/rest/v1/reference-entities/%s';
    const REFERENCE_ENTITIES_URI= 'api/rest/v1/reference-entities';

    /** @var ResourceClientInterface */
    private $resourceClient;

    /** @var PageFactoryInterface */
    private $pageFactory;

    /** @var ResourceCursorFactoryInterface */
    private $cursorFactory;

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

        //var_dump('ref');
        //var_dump($data);

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
