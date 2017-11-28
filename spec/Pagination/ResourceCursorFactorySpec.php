<?php

namespace spec\Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursor;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactory;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class ResourceCursorFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceCursorFactory::class);
        $this->shouldImplement(ResourceCursorFactoryInterface::class);
    }

    function it_creates_a_resource_cursor(PageInterface $page)
    {
        $this->createCursor(10, $page)->shouldBeLike(
            new ResourceCursor(10, $page->getWrappedObject())
        );
    }
}
