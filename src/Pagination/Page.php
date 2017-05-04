<?php

namespace Akeneo\Pim\Pagination;

use Akeneo\Pim\HttpClient\HttpClientInterface;

/**
 * Page represents a list of paginated resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Page
{
    /** @var PageFactoryInterface */
    protected $pageFactory;

    /** @var HttpClientInterface */
    protected $httpClient;

    /** @var string */
    protected $firstLink;

    /** @var string */
    protected $previousLink;

    /** @var string */
    protected $nextLink;

    /** @var integer */
    protected $count;

    /** @var array */
    protected $items;

    /**
     * @param PageFactoryInterface $pageFactory
     * @param HttpClientInterface  $httpClient
     * @param string               $firstLink
     * @param string|null          $previousLink
     * @param string|null          $nextLink
     * @param int|null             $count
     * @param array                $items
     */
    public function __construct(PageFactoryInterface $pageFactory, HttpClientInterface $httpClient, $firstLink, $previousLink, $nextLink, $count, array $items)
    {
        $this->pageFactory = $pageFactory;
        $this->httpClient = $httpClient;
        $this->firstLink = $firstLink;
        $this->previousLink = $previousLink;
        $this->nextLink = $nextLink;
        $this->count = $count;
        $this->items = $items;
    }

    /**
     * Returns the first page of the list of resources.
     *
     * @return Page
     */
    public function getFirstPage()
    {
        return $this->getPage($this->firstLink);
    }

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     *
     * @return Page|null
     */
    public function getPreviousPage()
    {
        return $this->hasPreviousPage() ? $this->getPage($this->previousLink) : null;
    }

    /**
     * Returns the previous page of the list of resources if it exists, null otherwise.
     *
     * @return Page|null
     */
    public function getNextPage()
    {
        return $this->hasNextPage() ? $this->getPage($this->nextLink) : null;
    }

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Returns true if a next page exists, false either.
     *
     * @return bool
     */
    public function hasNextPage()
    {
        return null !== $this->nextLink;
    }

    /**
     * Returns true if a previous page exists, false either.
     *
     * @return bool
     */
    public function hasPreviousPage()
    {
        return null !== $this->previousLink;
    }

    /**
     * Returns the link to the next page.
     * If there is no next page, returns null.
     *
     * @return null|string
     */
    public function getNextLink()
    {
        return $this->nextLink;
    }

    /**
     * Returns the link to the previous page.
     * If there is no previous page, returns null.
     *
     * @return null|string
     */
    public function getPreviousLink()
    {
        return $this->previousLink;
    }

    /**
     * Returns the page given a complete uri.
     *
     * @param string $uri
     *
     * @return Page
     */
     protected function getPage($uri)
    {
        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);
        $data = json_decode($response->getBody()->getContents(), true);

        return $this->pageFactory->createPage($data);
    }
}
