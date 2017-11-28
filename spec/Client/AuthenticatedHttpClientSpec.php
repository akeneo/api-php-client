<?php

namespace spec\Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Api\AuthenticationApiInterface;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Client\AuthenticatedHttpClient;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Client\HttpClientInterface;
use Akeneo\Pim\ApiClient\Security\Authentication;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

class AuthenticatedHttpClientSpec extends ObjectBehavior
{
    function let(
        HttpClient $httpClient,
        AuthenticationApiInterface $authenticationApi,
        Authentication $authentication
    ) {
        $this->beConstructedWith($httpClient, $authenticationApi, $authentication);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticatedHttpClient::class);
        $this->shouldImplement(HttpClientInterface::class);
    }

    function it_sends_an_authenticated_and_successful_request_when_access_token_is_defined(
        $httpClient,
        $authentication,
        ResponseInterface $response
    ) {
        $authentication->getAccessToken()->willReturn('bar');

        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer bar'],
            '{"identifier": "foo"}'
        )->willReturn($response);

        $this->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->shouldReturn($response);
    }

    function it_sends_an_authenticated_and_successful_request_at_first_call(
        $httpClient,
        $authenticationApi,
        $authentication,
        ResponseInterface $response
    ) {
        $authentication->getClientId()->willReturn('client_id');
        $authentication->getSecret()->willReturn('secret');
        $authentication->getUsername()->willReturn('julia');
        $authentication->getPassword()->willReturn('julia_pwd');
        $authentication->getAccessToken()->willReturn(null, 'foo');

        $authenticationApi
            ->authenticateByPassword('client_id', 'secret', 'julia', 'julia_pwd')
            ->willReturn([
                'access_token'  => 'foo',
                'expires_in'    => 3600,
                'token_type'    => 'bearer',
                'scope'         => null,
                'refresh_token' => 'bar',
            ]);

        $authentication
            ->setAccessToken('foo')
            ->shouldBeCalled()
            ->willReturn($authentication);

        $authentication
            ->setRefreshToken('bar')
            ->shouldBeCalled()
            ->willReturn($authentication);

        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer foo'],
            '{"identifier": "foo"}'
        )->willReturn($response);

        $this->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->shouldReturn($response);
    }

    function it_sends_an_authenticated_and_successful_request_when_access_token_expired(
        $httpClient,
        $authenticationApi,
        $authentication,
        ResponseInterface $response
    ) {
        $authentication->getClientId()->willReturn('client_id');
        $authentication->getSecret()->willReturn('secret');
        $authentication->getUsername()->willReturn('julia');
        $authentication->getPassword()->willReturn('julia_pwd');
        $authentication->getAccessToken()->willReturn('foo', 'foo', 'baz');
        $authentication->getRefreshToken()->willReturn('bar');

        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer foo'],
            '{"identifier": "foo"}'
        )->willThrow(UnauthorizedHttpException::class);

        $authenticationApi
            ->authenticateByRefreshToken('client_id', 'secret', 'bar')
            ->willReturn([
                'access_token'  => 'baz',
                'expires_in'    => 3600,
                'token_type'    => 'bearer',
                'scope'         => null,
                'refresh_token' => 'foz',
            ]);

        $authentication
            ->setAccessToken('baz')
            ->shouldBeCalled()
            ->willReturn($authentication);

        $authentication
            ->setRefreshToken('foz')
            ->shouldBeCalled()
            ->willReturn($authentication);

        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer baz'],
            '{"identifier": "foo"}'
        )->willReturn($response);

        $this->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        );
    }
}
