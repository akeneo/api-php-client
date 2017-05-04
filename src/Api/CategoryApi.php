<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PaginationParameter;
use Akeneo\Pim\Pagination\PaginationType;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Routing\UriGeneratorInterface;

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
    public function getCategories($limit = 10, $withCount = false, array $queryParameters = [])
    {
        $data = $this->resourceClient->getResources(static::CATEGORIES_PATH, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryCursor($pageSize = 10, array $queryParameters = [])
    {
        $firstPage = $this->getCategories($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function createCategory($code, array $data = [])
    {
        if (array_key_exists('code', $data)) {
            throw new \InvalidArgumentException('The parameter "code" should not be defined in the data parameter');
        }

        $data['code'] = $code;

        $this->resourceClient->createResource(static::CATEGORIES_PATH, [], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategory($code, array $data = [])
    {
        return $this->resourceClient->partialUpdateResource(static::CATEGORY_PATH, [$code], $data);
    }
}
