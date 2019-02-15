<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\CurrencyApi;
use Akeneo\Pim\ApiClient\Api\CurrencyApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class CurrencyApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CurrencyApi::class);
        $this->shouldImplement(CurrencyApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_currency($resourceClient)
    {
        $currencyCode = 'foo';
        $currency = [
            'code' => 'foo',
            'enabled' => true,
        ];

        $resourceClient
            ->getResource(CurrencyApi::CURRENCY_URI, [$currencyCode])
            ->willReturn($currency);

        $this->get($currencyCode)->shouldReturn($currency);
    }

    function it_returns_a_list_of_currencies_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CurrencyApi::CURRENCIES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_currencies_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CurrencyApi::CURRENCIES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_currencies(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(CurrencyApi::CURRENCIES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_currencies_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(CurrencyApi::CURRENCIES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }
}
