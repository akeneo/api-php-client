<?php

namespace spec\Akeneo\Pim\Pagination;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursor;
use PhpSpec\ObjectBehavior;

class ResourceCursorFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Pagination\ResourceCursorFactory');
        $this->shouldImplement('Akeneo\Pim\Pagination\ResourceCursorFactoryInterface');
    }

    function it_creates_a_resource_cursor(PageInterface $page)
    {
        $this->createCursor(10, $page)->shouldBeLike(
            new ResourceCursor(10, $page->getWrappedObject())
        );
    }
}
