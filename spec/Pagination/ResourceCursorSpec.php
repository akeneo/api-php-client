<?php

namespace spec\Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Pagination\Page;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursor;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class ResourceCursorSpec extends ObjectBehavior
{
    function let(PageInterface $firstPage)
    {
        $this->beConstructedWith(10, $firstPage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceCursor::class);
        $this->shouldImplement(ResourceCursorInterface::class);
    }

    function it_is_iterable($firstPage, Page $secondPage)
    {
        $this->shouldImplement('\Iterator');

        $firstPage->getItems()->willReturn([
            ['code' => 'foo'],
            ['code' => 'bar']
        ]);
        $firstPage->hasNextPage()->willReturn(true);
        $firstPage->getNextPage()->willReturn($secondPage);

        $secondPage->getItems()->willReturn([
            ['code' => 'baz'],
            ['code' => 'foz']
        ]);
        $secondPage->hasNextPage()->willReturn(false);

        // methods that not iterate can be called twice
        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'foo']);
        $this->current()->shouldReturn(['code' => 'foo']);
        $this->key()->shouldReturn(0);
        $this->key()->shouldReturn(0);

        // for each call sequence
        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'foo']);
        $this->key()->shouldReturn(0);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'bar']);
        $this->key()->shouldReturn(1);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'baz']);
        $this->key()->shouldReturn(2);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'foz']);
        $this->key()->shouldReturn(3);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(false);

        // check that rewind is working
        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['code' => 'foo']);
        $this->key()->shouldReturn(0);
    }

    function it_gets_page_size()
    {
        $this->getPageSize()->shouldReturn(10);
    }

}
