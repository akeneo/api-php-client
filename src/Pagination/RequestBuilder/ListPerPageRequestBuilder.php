<?php

namespace Akeneo\Pim\Pagination\RequestBuilder;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;

/**
 * Class to build a request in order to get a page containing a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ListPerPageRequestBuilder
{
    /** @var ResourceClientInterface  */
    protected $resourceClient;

    /** @var PageFactoryInterface  */
    protected $pageFactory;

    /** @var string */
    protected $uri;

    /** @var array */
    protected $uriParameters = [];

    /** @var array */
    protected $queryParameters = [];

    /** @var int  */
    protected $limit = 10;

    /** @var bool */
    protected $withCount = false;

    /**
     * @param ResourceClientInterface $resourceClient
     * @param PageFactoryInterface    $pageFactory
     * @param string                  $uri
     * @param array                   $uriParameters
     */
    public function __construct(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        $uri,
        array $uriParameters = []
    ) {
        $this->resourceClient = $resourceClient;
        $this->pageFactory = $pageFactory;
        $this->uri = $uri;
        $this->uriParameters = $uriParameters;
    }

    /**
     * Returns the total count of resources.
     * It can decrease drastically the performance.
     *
     * @return ListPerPageRequestBuilder
     */
    public function withCount()
    {
        $this->withCount = true;

        return $this;
    }

    /**
     * Does not return the total count of resources.
     *
     * @return ListPerPageRequestBuilder
     */
    public function withoutCount()
    {
        $this->withCount = false;

        return $this;
    }

    /**
     * Defines the maximum number of resources to return per page.
     * Do note that the server has a default value if you don't specify anything.
     *
     * The server has a maximum limit allowed as well.
     *
     * @param int $limit The number of resources to return in a page
     *
     * @return ListPerPageRequestBuilder
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Adds an additional query parameter to the request.
     *
     * @param string $key
     * @param string $value
     *
     * @return ListPerPageRequestBuilder
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

        $queryParameters = array_merge($this->queryParameters, ['limit' => $this->limit, 'with_count' => $this->withCount]);

        $data = $this->resourceClient->getResource($this->uri, $this->uriParameters, $queryParameters);

        return $this->pageFactory->createPage($data);
    }
}
