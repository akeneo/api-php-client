<?php

namespace spec\Akeneo\Pim\Pagination;

use Akeneo\Pim\Pagination\Page;
use PhpSpec\ObjectBehavior;

class PageFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Pagination\PageFactory');
        $this->shouldHaveType('Akeneo\Pim\Pagination\PageFactoryInterface');
    }

    function it_creates_a_page_with_all_links()
    {
        $data = [
            '_links'      => [
                'self'     => [
                    'href' => 'http://akeneo.com/self',
                ],
                'first'    => [
                    'href' => 'http://akeneo.com/first',
                ],
                'previous' => [
                    'href' => 'http://akeneo.com/previous',
                ],
                'next'     => [
                    'href' => 'http://akeneo.com/next',
                ],
            ],
            'items_count' => 10,
            '_embedded'   => [
                'items' => [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            ],
        ];

        $this->createPage($data)->shouldReturnAnInstanceOf(Page::class);
        $this->createPage($data)->shouldBeLike(
            new Page(
                'http://akeneo.com/self',
                'http://akeneo.com/first',
                'http://akeneo.com/previous',
                'http://akeneo.com/next',
                10,
                [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            )
        );
    }

    function it_creates_a_page_without_next_and_previous_links()
    {
        $data = [
            '_links'      => [
                'self'     => [
                    'href' => 'http://akeneo.com/self',
                ],
                'first'    => [
                    'href' => 'http://akeneo.com/first',
                ],
            ],
            'items_count' => 10,
            '_embedded'   => [
                'items' => [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            ],
        ];

        $this->createPage($data)->shouldReturnAnInstanceOf(Page::class);
        $this->createPage($data)->shouldBeLike(
            new Page(
                'http://akeneo.com/self',
                'http://akeneo.com/first',
                null,
                null,
                10,
                [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            )
        );
    }

    function it_creates_a_page_without_count()
    {
        $data = [
            '_links'      => [
                'self'     => [
                    'href' => 'http://akeneo.com/self',
                ],
                'first'    => [
                    'href' => 'http://akeneo.com/first',
                ],
                'previous' => [
                    'href' => 'http://akeneo.com/previous',
                ],
                'next'     => [
                    'href' => 'http://akeneo.com/next',
                ],
            ],
            '_embedded'   => [
                'items' => [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            ],
        ];

        $this->createPage($data)->shouldReturnAnInstanceOf(Page::class);
        $this->createPage($data)->shouldBeLike(
            new Page(
                'http://akeneo.com/self',
                'http://akeneo.com/first',
                'http://akeneo.com/previous',
                'http://akeneo.com/next',
                null,
                [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ]
            )
        );
    }
}
