<?php

namespace Akeneo\Pim\Pagination\RequestBuilder;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;

/**
 * Default factory to create a request builder for a resource.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceRequestBuilderFactory
{
    /** @var ResourceClientInterface  */
    protected $resourceClient;

    /** @var PageFactoryInterface  */
    protected $pageFactory;

    /** @var ResourceCursorFactoryInterface  */
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
     * Create a builder to get a list of resources page per page.
     *
     * @param string $uri URI of the resource
     *
     * @return ListPerPageRequestBuilder
     */
    public function createListPerPageBuilder($uri)
    {
        return new ListPerPageRequestBuilder($this->resourceClient, $this->pageFactory, $uri);

    }

    /**
     * Create a builder to get a list of resources with an iterator.
     *
     * @param string $uri URI of the resource
     *
     * @return ListAllRequestBuilder
     */
    public function createListAllBuilder($uri)
    {
        return new ListAllRequestBuilder($this->resourceClient, $this->pageFactory, $this->cursorFactory, $uri);
    }
}
