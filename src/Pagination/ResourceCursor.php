<?php

namespace Akeneo\Pim\ApiClient\Pagination;

/**
 * Cursor to iterate over a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceCursor implements ResourceCursorInterface
{
    protected PageInterface $currentPage;
    protected PageInterface $firstPage;
    /** @var null|int */
    protected $pageSize;
    protected int $currentIndex = 0;
    protected int $totalIndex = 0;

    public function __construct(?int $pageSize, PageInterface $firstPage)
    {
        $this->firstPage = $firstPage;
        $this->currentPage = $firstPage;
        $this->pageSize = $pageSize;
    }

    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function current()
    {
        return $this->currentPage->getItems()[$this->currentIndex];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->currentIndex++;
        $this->totalIndex++;

        $items = $this->currentPage->getItems();

        if (!isset($items[$this->currentIndex]) && $this->currentPage->hasNextPage()) {
            $this->currentIndex = 0;
            $this->currentPage = $this->currentPage->getNextPage();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->totalIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return isset($this->currentPage->getItems()[$this->currentIndex]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->totalIndex = 0;
        $this->currentIndex = 0;
        $this->currentPage = $this->firstPage;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }
}
