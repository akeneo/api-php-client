<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\SystemInformationApi;
use Akeneo\Pim\ApiClient\Api\SystemInformationApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use PhpSpec\ObjectBehavior;

class SystemInformationApiSpec extends ObjectBehavior
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
        $this->shouldHaveType(SystemInformationApi::class);
        $this->shouldImplement(SystemInformationApiInterface::class);
    }

    function it_returns_system_information($resourceClient)
    {
        $systemInformation = [
            'code'       => 'foo',
            'attributes' => ['sku'],
            'sort_order' => 1,
            'labels'     => [
                'en_US' => 'Foo',
            ],
        ];

        $resourceClient
            ->getResource(SystemInformationApi::SYSTEM_INFORMATION_URI)
            ->willReturn($systemInformation);

        $this->get()->shouldReturn($systemInformation);
    }
}
