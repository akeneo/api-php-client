<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\RequestBuilder\ResourceRequestBuilderFactory;

/**
 * API implementation to manage the categories.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryApi implements CategoryApiInterface
{
    const CATEGORY_PATH = 'api/rest/v1/categories/%s';
    const CATEGORIES_PATH = 'api/rest/v1/categories';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /** @var ResourceRequestBuilderFactory */
    protected $requestBuilderFactory;

    /**
     * @param ResourceClientInterface       $resourceClient
     * @param ResourceRequestBuilderFactory $requestBuilderFactory
     */
    public function __construct(
        ResourceClientInterface $resourceClient,
        ResourceRequestBuilderFactory $requestBuilderFactory
    ) {
        $this->resourceClient = $resourceClient;
        $this->requestBuilderFactory = $requestBuilderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($code)
    {
        return $this->resourceClient->getResource(static::CATEGORY_PATH, [$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function listPerPage($limit = 10, $withCount = false, array $queryParameters = [])
    {
        return $this->requestBuilderFactory->createListPerPageBuilder(static::CATEGORIES_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function all($pageSize = 10, array $queryParameters = [])
    {
        return $this->requestBuilderFactory->createListAllBuilder(static::CATEGORIES_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function create($code, array $data = [])
    {
        if (array_key_exists('code', $data)) {
            throw new \InvalidArgumentException('The parameter "code" should not be defined in the data parameter');
        }

        $data['code'] = $code;

        return $this->resourceClient->createResource(static::CATEGORIES_PATH, [], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($code, array $data = [])
    {
        return $this->resourceClient->upsertResource(static::CATEGORY_PATH, [$code], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList($categories)
    {
        return $this->resourceClient->upsertResourceList(static::CATEGORIES_PATH, [], $categories);
    }
}
