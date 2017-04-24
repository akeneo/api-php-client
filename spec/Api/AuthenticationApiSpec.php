<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AuthenticationApi;
use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class AuthenticationApiSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, UriGeneratorInterface $uriGenerator)
    {
        $this->beConstructedWith($httpClient, $uriGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Api\AuthenticationApi');
        $this->shouldImplement('Akeneo\Pim\Api\AuthenticationApiInterface');
    }

    function it_authenticates_with_the_password_grant_type(
        $uriGenerator,
        $httpClient,
        ResponseInterface $response,
        StreamInterface $body
    ) {
        $uriGenerator->generate(AuthenticationApi::TOKEN_URI)->willReturn('http://akeneo.com/api/oauth/v1/token');
        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/oauth/v1/token',
            [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Basic %s', base64_encode('client_id:secret'))
            ],
            '{"grant_type":"password","username":"julia","password":"julia_pwd"}'
        )->willReturn($response);

        $response->getBody()->willReturn($body);
        $responseContent = <<<JSON
            {
                "access_token": "foo",
                "expires_in": 3600,
                "token_type": "bearer",
                "scope": null,
                "refresh_token": "bar"
            }
JSON;
        $body->getContents()->willReturn($responseContent);

        $this->authenticateByPassword('client_id', 'secret', 'julia', 'julia_pwd')->shouldReturn([
            'access_token'  => 'foo',
            'expires_in'    => 3600,
            'token_type'    => 'bearer',
            'scope'         => null,
            'refresh_token' => 'bar',
        ]);
    }

    function it_authenticates_with_the_refresh_token_type(
        $uriGenerator,
        $httpClient,
        ResponseInterface $response,
        StreamInterface $body
    ) {
        $uriGenerator->generate(AuthenticationApi::TOKEN_URI)->willReturn('http://akeneo.com/api/oauth/v1/token');
        $httpClient->sendRequest(
            'POST',
            'http://akeneo.com/api/oauth/v1/token',
            [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Basic %s', base64_encode('client_id:secret'))
            ],
            '{"grant_type":"refresh_token","refresh_token":"bar"}'
        )->willReturn($response);

        $response->getBody()->willReturn($body);
        $responseContent = <<<JSON
            {
                "access_token": "foo",
                "expires_in": 3600,
                "token_type": "bearer",
                "scope": null,
                "refresh_token": "baz"
            }
JSON;
        $body->getContents()->willReturn($responseContent);

        $this->authenticateByRefreshToken('client_id', 'secret', 'bar')->shouldReturn([
            'access_token'  => 'foo',
            'expires_in'    => 3600,
            'token_type'    => 'bearer',
            'scope'         => null,
            'refresh_token' => 'baz',
        ]);
    }
}
