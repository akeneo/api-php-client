<?php

namespace spec\Akeneo\Pim\Pagination;

use PhpSpec\ObjectBehavior;

class PageSpec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith(
            'http://akeneo.com/self',
            'http://akeneo.com/first',
            'http://akeneo.com/previous',
            'http://akeneo.com/next',
            10,
            [
                ['identifier' => 'foo'],
                ['identifier' => 'bar'],
            ]
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Pagination\Page');
    }

    function it_allows_to_get_self_link()
    {
        $this->getSelfLink()->shouldReturn('http://akeneo.com/self');
    }

    function it_allows_to_get_first_link()
    {
        $this->getFirstLink()->shouldReturn('http://akeneo.com/first');
    }

    function it_allows_to_get_previous_link()
    {
        $this->getPreviousLink()->shouldReturn('http://akeneo.com/previous');
    }

    function it_allows_to_get_next_link()
    {
        $this->getNextLink()->shouldReturn('http://akeneo.com/next');
    }

    function it_allows_to_get_items()
    {
        $this->getItems()->shouldReturn([
            ['identifier' => 'foo'],
            ['identifier' => 'bar'],
        ]);
    }
}
