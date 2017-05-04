<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\Client\AkeneoPimClientBuilder;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use PhpSpec\ObjectBehavior;

class AkeneoPimClientBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('http://akeneo.com/', 'client_id', 'secret', 'username', 'password');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AkeneoPimClientBuilder::class);
    }

    function it_builds_an_akeneo_pim_client()
    {
        $this->build()->shouldReturnAnInstanceOf(AkeneoPimClientInterface::class);
    }
}
