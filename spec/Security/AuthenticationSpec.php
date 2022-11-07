<?php

namespace spec\Akeneo\Pim\ApiClient\Security;

use Akeneo\Pim\ApiClient\Security\Authentication;
use PhpSpec\ObjectBehavior;

class AuthenticationSpec extends ObjectBehavior
{
    function it_is_initializable_from_a_password()
    {
        $this->beConstructedThrough('fromPassword', ['client_id', 'secret', 'Julia', 'Julia_pwd']);
        $this->shouldHaveType(Authentication::class);

        $this->getClientId()->shouldReturn('client_id');
        $this->getSecret()->shouldReturn('secret');
        $this->getUsername()->shouldReturn('Julia');
        $this->getPassword()->shouldReturn('Julia_pwd');
        $this->getAccessToken()->shouldReturn(null);
        $this->getRefreshToken()->shouldReturn(null);
    }

    function it_is_initializable_from_a_token()
    {
        $this->beConstructedThrough('fromToken', ['client_id', 'secret', 'token', 'refresh_token']);
        $this->shouldHaveType(Authentication::class);

        $this->getClientId()->shouldReturn('client_id');
        $this->getSecret()->shouldReturn('secret');
        $this->getUsername()->shouldReturn(null);
        $this->getPassword()->shouldReturn(null);
        $this->getAccessToken()->shouldReturn('token');
        $this->getRefreshToken()->shouldReturn('refresh_token');
    }

    function it_is_initializable_from_an_app_token()
    {
        $this->beConstructedThrough('fromAppToken', ['a_token']);
        $this->shouldHaveType(Authentication::class);

        $this->getClientId()->shouldReturn(null);
        $this->getSecret()->shouldReturn(null);
        $this->getUsername()->shouldReturn(null);
        $this->getPassword()->shouldReturn(null);
        $this->getAccessToken()->shouldReturn('a_token');
        $this->getRefreshToken()->shouldReturn(null);
    }
}
