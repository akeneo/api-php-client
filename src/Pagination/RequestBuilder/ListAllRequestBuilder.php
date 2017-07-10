<?php

namespace Akeneo\Pim\Pagination\RequestBuilder;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;

/**
 * Class to build a request in order to get an iterator on a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ListAllRequestBuilder
{
    /** @var ResourceClientInterface  */
    protected $resourceClient;

    /** @var PageFactoryInterface  */
    protected $pageFactory;

    /** @var ResourceCursorFactoryInterface  */
    protected $cursorFactory;

    /** @var string */
    protected $uri;

    /** @var array */
    protected $uriParameters = [];

    /** @var array */
    protected $queryParameters = [];

    /** @var int  */
    protected $pageSize = 10;

    /** @var bool */
    protected $withCount = false;

    /**
     * @param ResourceClientInterface        $resourceClient
     * @param PageFactoryInterface           $pageFactory
     * @param ResourceCursorFactoryInterface $cursorFactory
     * @param string                         $uri
     * @param array                          $uriParameters
     */
    public function __construct(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory,
        $uri,
        array $uriParameters = []
    ) {
        $this->resourceClient = $resourceClient;
        $this->pageFactory = $pageFactory;
        $this->cursorFactory = $cursorFactory;
        $this->uri = $uri;
        $this->uriParameters = $uriParameters;
    }

    /**
     * Defines the size of the page when iterating the list of resources.
     * Do note that the server has a maximum limit allowed.
     *
     * @param int $pageSize The page of the size
     *
     * @return ListAllRequestBuilder
     */
    public function pageSize($pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * Adds an additional query parameter to the request.
     *
     * @param string $key
     * @param string $value
     *
     * @return ListAllRequestBuilder
     */
    public function addQueryParameter($key, $value)
    {
        $this->queryParameters[$key] = $value;

        return $this;
    }

    /**
     * Gets the page.
     *
     * @throws \InvalidArgumentException
     *
     * @return PageInterface
     */
    public function get()
    {
        if (array_key_exists('limit', $this->queryParameters)) {
            throw new \InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters');
        }

        if (array_key_exists('with_count', $this->queryParameters)) {
            throw new \InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters');
        }

        $queryParameters = array_merge($this->queryParameters, ['limit' => $this->pageSize, 'with_count' => false]);

        $data = $this->resourceClient->getResource($this->uri, $this->uriParameters, $queryParameters);
        $firstPage = $this->pageFactory->createPage($data);

        return $this->cursorFactory->createCursor($this->pageSize, $firstPage);
    }
}
