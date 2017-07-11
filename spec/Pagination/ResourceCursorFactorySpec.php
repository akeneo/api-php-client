<?php

namespace spec\Akeneo\Pim\Pagination;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursor;
use Akeneo\Pim\Pagination\ResourceCursorFactory;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
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
