<?php

namespace Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Client\HttpClientInterface;

/**
 * Page represents a list of paginated resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Page implements PageInterface
{
    public function __construct(
        protected PageFactoryInterface $pageFactory,
        protected HttpClientInterface $httpClient,
        protected string $firstLink,
        protected ?string$previousLink,
        protected ?string $nextLink,
        protected ?int $count,
        protected array $items
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstPage(): PageInterface
    {
        return $this->getPage($this->firstLink);
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousPage(): ?PageInterface
    {
        return $this->hasPreviousPage() ? $this->getPage($this->previousLink) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextPage(): ?PageInterface
    {
        return $this->hasNextPage() ? $this->getPage($this->nextLink) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNextPage(): bool
    {
        return null !== $this->nextLink;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPreviousPage(): bool
    {
        return null !== $this->previousLink;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextLink(): ?string
    {
        return $this->nextLink;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousLink(): ?string
    {
        return $this->previousLink;
    }

    /**
     * Returns the page given a complete uri.
     */
    protected function getPage(string $uri): PageInterface
    {
        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);
        $data = json_decode($response->getBody()->getContents(), true);

        return $this->pageFactory->createPage($data);
    }
}
