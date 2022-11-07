<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * API implementation to manage the product models.
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductModelApi implements ProductModelApiInterface
{
    public const PRODUCT_MODEL_URI = 'api/rest/v1/product-models/%s';
    public const PRODUCT_MODELS_URI = 'api/rest/v1/product-models';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
        protected PageFactoryInterface $pageFactory,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function get(string $code): array
    {
        return $this->resourceClient->getResource(static::PRODUCT_MODEL_URI, [$code]);
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function create(string $code, array $data = []): int
    {
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" must not be defined in the data parameter');
        }

        $data['code'] = $code;

        return $this->resourceClient->createResource(static::PRODUCT_MODELS_URI, [], $data);
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function upsert(string $code, array $data = []): int
    {
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" must not be defined in the data parameter');
        }

        $data['code'] = $code;

        return $this->resourceClient->upsertResource(static::PRODUCT_MODEL_URI, [$code], $data);
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function listPerPage(int $limit = 100, bool $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(static::PRODUCT_MODELS_URI, [], $limit, $withCount, $queryParameters);

        return $this->pageFactory->createPage($data);
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function all(int $pageSize = 100, array $queryParameters = []): ResourceCursorInterface
    {
        $queryParameters['pagination_type'] = 'search_after';

        $firstPage = $this->listPerPage($pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    /**
     * Available since Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function upsertList($resources): \Traversable
    {
        return $this->resourceClient->upsertStreamResourceList(static::PRODUCT_MODELS_URI, [], $resources);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $code): int
    {
        return $this->resourceClient->deleteResource(static::PRODUCT_MODEL_URI, [$code]);
    }
}
