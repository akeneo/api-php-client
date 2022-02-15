<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2018 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceEntityRecordApi implements ReferenceEntityRecordApiInterface
{
    const REFERENCE_ENTITY_RECORD_URI = 'api/rest/v1/reference-entities/%s/records/%s';
    const REFERENCE_ENTITY_RECORDS_URI = 'api/rest/v1/reference-entities/%s/records';

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
    public function get(string $referenceEntityCode, string $recordCode): array
    {
        return $this->resourceClient->getResource(static::REFERENCE_ENTITY_RECORD_URI, [$referenceEntityCode, $recordCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $referenceEntityCode, array $queryParameters = []): ResourceCursorInterface
    {
        $data = $this->resourceClient->getResources(
            static::REFERENCE_ENTITY_RECORDS_URI,
            [$referenceEntityCode],
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
    public function upsert(string $referenceEntityCode, string $recordCode, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::REFERENCE_ENTITY_RECORD_URI, [$referenceEntityCode, $recordCode], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList(string $referenceEntityCode, array $records): array
    {
        return $this->resourceClient->upsertJsonResourceList(static::REFERENCE_ENTITY_RECORDS_URI, [$referenceEntityCode], $records);
    }
}
