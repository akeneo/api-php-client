<?php

namespace spec\Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Client\HttpClientInterface;
use Akeneo\Pim\ApiClient\Pagination\Page;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PageSpec extends ObjectBehavior
{
    function let(PageFactoryInterface $pageFactory, HttpClientInterface $httpClient)
    {
        $this->beConstructedWith(
            $pageFactory,
            $httpClient,
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
        $this->shouldHaveType(Page::class);
        $this->shouldImplement(PageInterface::class);
    }

    function it_gets_next_page(
        $httpClient,
        $pageFactory,
        ResponseInterface $response,
        StreamInterface $stream,
        Page $nextPage
    ) {
        $nextPageContent = $this->getPageSample();
        $httpClient->sendRequest('GET', 'http://akeneo.com/next', ['Accept' => '*/*'])->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode($nextPageContent));
        $pageFactory->createPage($nextPageContent)->willReturn($nextPage);

        $this->getNextPage()->shouldReturn($nextPage);
    }

    function it_returns_null_when_getting_nonexistent_next_page($httpClient, $pageFactory)
    {
        $this->beConstructedWith(
            $pageFactory,
            $httpClient,
            'http://akeneo.com/first',
            'http://akeneo.com/previous',
            null,
            10,
            [
                ['identifier' => 'foo'],
                ['identifier' => 'bar'],
            ]
        );

        $this->getNextPage()->shouldReturn(null);
    }

    function it_gets_previous_page(
        $httpClient,
        $pageFactory,
        ResponseInterface $response,
        StreamInterface $stream,
        Page $previousPage
    ) {
        $previousPageContent = $this->getPageSample();
        $httpClient->sendRequest('GET', 'http://akeneo.com/previous', ['Accept' => '*/*'])->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode($previousPageContent));
        $pageFactory->createPage($previousPageContent)->willReturn($previousPage);

        $this->getPreviousPage()->shouldReturn($previousPage);
    }

    function it_returns_null_when_getting_nonexistent_previous_page($httpClient, $pageFactory)
    {
        $this->beConstructedWith(
            $pageFactory,
            $httpClient,
            'http://akeneo.com/first',
            null,
            'http://akeneo.com/next',
            10,
            [
                ['identifier' => 'foo'],
                ['identifier' => 'bar'],
            ]
        );

        $this->getPreviousPage()->shouldReturn(null);
    }

    function it_gets_first_page(
        $httpClient,
        $pageFactory,
        ResponseInterface $response,
        StreamInterface $stream,
        Page $firstPage
    ) {
        $firstPageContent = $this->getPageSample();
        $httpClient->sendRequest('GET', 'http://akeneo.com/first', ['Accept' => '*/*'])->willReturn($response);
        $response->getBody()->willReturn($stream);
        $stream->getContents()->willReturn(json_encode($firstPageContent));
        $pageFactory->createPage($firstPageContent)->willReturn($firstPage);

        $this->getFirstPage()->shouldReturn($firstPage);
    }

    function it_gets_items()
    {
        $this->getItems()->shouldReturn([
            ['identifier' => 'foo'],
            ['identifier' => 'bar'],
        ]);
    }

    function it_gets_count()
    {
        $this->getCount()->shouldReturn(10);
    }

    function it_has_next_page()
    {
        $this->hasNextPage()->shouldReturn(true);
    }

    function it_does_not_have_next_page($pageFactory, $httpClient)
    {
        $this->beConstructedWith(
            $pageFactory,
            $httpClient,
            'http://akeneo.com/first',
            'http://akeneo.com/previous',
            null,
            10,
            [
                ['identifier' => 'foo'],
                ['identifier' => 'bar'],
            ]
        );
        $this->hasNextPage()->shouldReturn(false);
    }

    function it_does_not_have_previous_page($pageFactory, $httpClient)
    {
        $this->beConstructedWith(
            $pageFactory,
            $httpClient,
            'http://akeneo.com/first',
            null,
            'http://akeneo.com/next',
            10,
            [
                ['identifier' => 'foo'],
                ['identifier' => 'bar'],
            ]
        );
        $this->hasPreviousPage()->shouldReturn(false);
    }

    function it_has_previous_page()
    {
        $this->hasPreviousPage()->shouldReturn(true);
    }

    function it_gets_next_link()
    {
        $this->getNextLink()->shouldReturn('http://akeneo.com/next');
    }

    function it_gets_previous_link()
    {
        $this->getPreviousLink()->shouldReturn('http://akeneo.com/previous');
    }

    protected function getPageSample()
    {
        return [
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
                ],
            ],
        ];
    }
}
