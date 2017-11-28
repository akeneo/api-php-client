<?php

namespace spec\Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Client\HttpClientInterface;
use Akeneo\Pim\ApiClient\Pagination\Page;
use Akeneo\Pim\ApiClient\Pagination\PageFactory;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use PhpSpec\ObjectBehavior;

class PageFactorySpec extends ObjectBehavior
{
    public function let(HttpClientInterface $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PageFactory::class);
        $this->shouldImplement(PageFactoryInterface::class);
    }

    function it_creates_a_page_with_all_links($httpClient)
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
                new PageFactory($httpClient->getWrappedObject()),
                $httpClient->getWrappedObject(),
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

    function it_creates_a_page_without_next_and_previous_links($httpClient)
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
                new PageFactory($httpClient->getWrappedObject()),
                $httpClient->getWrappedObject(),
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

    function it_creates_a_page_without_count($httpClient)
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
                new PageFactory($httpClient->getWrappedObject()),
                $httpClient->getWrappedObject(),
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
