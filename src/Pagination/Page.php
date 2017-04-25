<?php

namespace Akeneo\Pim\Pagination;

/**
 * Page represents a list of resources, with the different links to the other related page.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Page
{
    /** @var string */
    protected $selfLink;

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
     * @param string      $selfLink
     * @param string      $firstLink
     * @param string|null $previousLink
     * @param string|null $nextLink
     * @param int|null    $count
     * @param array       $items
     */
    public function __construct($selfLink, $firstLink, $previousLink, $nextLink, $count, array $items)
    {
        $this->selfLink = $selfLink;
        $this->firstLink = $firstLink;
        $this->previousLink = $previousLink;
        $this->nextLink = $nextLink;
        $this->count = $count;
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }

    /**
     * @return string
     */
    public function getFirstLink()
    {
        return $this->firstLink;
    }

    /**
     * @return string|null
     */
    public function getPreviousLink()
    {
        return $this->previousLink;
    }

    /**
     * @return string|null
     */
    public function getNextLink()
    {
        return $this->nextLink;
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
}
