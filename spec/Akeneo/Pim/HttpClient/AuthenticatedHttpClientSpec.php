<?php

namespace spec\Akeneo\Pim\HttpClient;

use Akeneo\Pim\Routing\UriGeneratorInterface;
use Akeneo\Pim\Security\Authentication;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class AuthenticatedHttpClientSpec extends ObjectBehavior
{
    function let(
        HttpClient $httpClient,
        UriGeneratorInterface $uriGenerator,
        RequestFactory $requestFactory,
        Authentication $authentication
    ) {
        $this->beConstructedWith($httpClient, $uriGenerator, $requestFactory, $authentication);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Client\ResourceClient');
    }

    function it_sends_a_request_without_authentication(
        $requestFactory,
        $httpClient,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->willReturn($request);

        $httpClient->sendRequest($request)->willReturn($response);

        $this->sendRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->shouldReturn($response);
    }

    function it_sends_an_authenticated_request_by_getting_the_access_token_when_it_is_the_first_request(
        $requestFactory,
        $httpClient,
        $authentication,
        $uriGenerator,
        RequestInterface $authenticationRequest,
        ResponseInterface $authenticationResponse,
        RequestInterface $requestWithoutToken,
        RequestInterface $requestWithToken,
        ResponseInterface $response,
        StreamInterface $body
    ) {
        $authentication->getUsername()->willReturn('julia');
        $authentication->getPassword()->willReturn('julia_pwd');
        $authentication->getClientId()->willReturn('client_id');
        $authentication->getSecret()->willReturn('secret_id');

        $uriGenerator->generate('api/oauth/v1/token')->willReturn('http://akeneo.com/api/oauth/v1/token');

        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/oauth/v1/token',
            [
                'Authorization' => sprintf('Basic %s', base64_encode('client_id:secret_id')),
                'Content-Type'  => 'application/json',
            ],
            '{"grant_type":"password","username":"julia","password":"julia_pwd"}'
        )->willReturn($authenticationRequest);

        $authenticationResponse->getStatusCode()->willReturn(200);
        $authenticationResponse->getBody()->willReturn($body);

        $body->getContents()->willReturn(<<<JSON
            {
                "access_token": "foo",
                "expires_in": 3600,
                "token_type": "bearer",
                "scope": null,
                "refresh_token": "bar"
            }
JSON
        );

        $httpClient->sendRequest($authenticationRequest)->willReturn($authenticationResponse);


        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->willReturn($requestWithoutToken);

        $requestWithoutToken->withHeader('Authorization', 'Bearer foo')->willReturn($requestWithToken);

        $httpClient->sendRequest($requestWithToken)->willReturn($response);

        $this->sendAuthenticatedRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->shouldReturn($response);
    }

    function it_sends_an_authenticated_request_by_refreshing_the_access_token_when_acces_token_is_expired(
        $requestFactory,
        $httpClient,
        $authentication,
        $uriGenerator,
        RequestInterface $authenticationRequest,
        ResponseInterface $authenticationResponse,
        RequestInterface $requestWithoutToken,
        RequestInterface $requestWithToken,
        ResponseInterface $response,
        StreamInterface $body
    ) {
        $authentication->getUsername()->willReturn('julia');
        $authentication->getPassword()->willReturn('julia_pwd');
        $authentication->getClientId()->willReturn('client_id');
        $authentication->getSecret()->willReturn('secret_id');

        $uriGenerator->generate('api/oauth/v1/token')->willReturn('http://akeneo.com/api/oauth/v1/token');

        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/oauth/v1/token',
            [
                'Authorization' => sprintf('Basic %s', base64_encode('client_id:secret_id')),
                'Content-Type'  => 'application/json',
            ],
            '{"grant_type":"password","username":"julia","password":"julia_pwd"}'
        )->willReturn($authenticationRequest);

        $authenticationResponse->getStatusCode()->willReturn(200);
        $authenticationResponse->getBody()->willReturn($body);

        $body->getContents()->willReturn(<<<JSON
            {
                "access_token": "foo",
                "expires_in": 3600,
                "token_type": "bearer",
                "scope": null,
                "refresh_token": "bar"
            }
JSON
        );

        $httpClient->sendRequest($authenticationRequest)->willReturn($authenticationResponse);


        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->willReturn($requestWithoutToken);

        $requestWithoutToken->withHeader('Authorization', 'Bearer foo')->willReturn($requestWithToken);

        $httpClient->sendRequest($requestWithToken)->willReturn($response);

        $this->sendAuthenticatedRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->shouldReturn($response);
    }
}
