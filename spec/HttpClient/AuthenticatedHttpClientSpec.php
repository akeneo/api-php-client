<?php

namespace spec\Akeneo\Pim\HttpClient;

use Akeneo\Pim\Api\AuthenticationApiInterface;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\Security\Authentication;
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
        $this->shouldHaveType('Akeneo\Pim\HttpClient\AuthenticatedHttpClient');
        $this->shouldImplement('Akeneo\Pim\HttpClient\HttpClientInterface');
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

        $authenticationApi
            ->authenticateByPassword('client_id', 'secret', 'julia', 'julia_pwd')
            ->willReturn([
                'access_token'  => 'foo',
                'expires_in'    => 3600,
                'token_type'    => 'bearer',
                'scope'         => null,
                'refresh_token' => 'bar',
            ]);

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

        $authenticationApi
            ->authenticateByPassword('client_id', 'secret', 'julia', 'julia_pwd')
            ->willReturn([
                'access_token'  => 'foo',
                'expires_in'    => 3600,
                'token_type'    => 'bearer',
                'scope'         => null,
                'refresh_token' => 'bar',
            ]);

        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer foo'],
            '{"identifier": "foo"}'
        );

        // initialize the state of the client at first call with the access token
        $this->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        );

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
