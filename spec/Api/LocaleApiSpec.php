<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\LocaleApi;
use Akeneo\Pim\ApiClient\Api\LocaleApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;

class LocaleApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(LocaleApi::class);
        $this->shouldImplement(LocaleApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_locale($resourceClient)
    {
        $localeCode = 'en_US';
        $locale = [
            'code' => 'en_US',
            'enabled' => true,
        ];

        $resourceClient
            ->getResource(LocaleApi::LOCALE_URI, [$localeCode])
            ->willReturn($locale);

        $this->get($localeCode)->shouldReturn($locale);
    }

    function it_returns_a_list_of_locales_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(LocaleApi::LOCALES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_locales_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(LocaleApi::LOCALES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_locales(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(LocaleApi::LOCALES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_locales_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(LocaleApi::LOCALES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }
}
