<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * @author    Philippe Mossière <philippe.mossiere@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AssociationTypeApi implements AssociationTypeApiInterface
{
    const ASSOCIATION_TYPES_URI = 'api/rest/v1/association-types';
    const ASSOCIATION_TYPE_URI = 'api/rest/v1/association-types/%s';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /** @var PageFactoryInterface */
    protected $pageFactory;

    /** @var ResourceCursorFactoryInterface */
    protected $cursorFactory;

    /**
     * @param ResourceClientInterface        $resourceClient
     * @param PageFactoryInterface           $pageFactory
     * @param ResourceCursorFactoryInterface $cursorFactory
     */
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
        return $this->resourceClient->getResource(static::ASSOCIATION_TYPE_URI, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function listPerPage(int $limit = 10, bool $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(static::ASSOCIATION_TYPES_URI, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function all(int $pageSize = 10, array $queryParameters = []): ResourceCursorInterface
    {
        $firstPage = $this->listPerPage($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $code, array $data = []): int
    {
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" should not be defined in the data parameter');
        }

        $data['code'] = $code;

        return $this->resourceClient->createResource(static::ASSOCIATION_TYPES_URI, [], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert(string $code, array $data = []): int
    {
        return $this->resourceClient->upsertResource(static::ASSOCIATION_TYPE_URI, [$code], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList($associationTypes): \Traversable
    {
        return $this->resourceClient->upsertStreamResourceList(static::ASSOCIATION_TYPES_URI, [], $associationTypes);
    }
}
