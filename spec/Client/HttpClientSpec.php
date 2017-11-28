<?php

namespace spec\Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\HttpException;
use Akeneo\Pim\ApiClient\Client\HttpClientInterface;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpClientSpec extends ObjectBehavior
{
    function let(
        HttpClient $httpClient,
        RequestFactory $requestFactory
    ) {
        $this->beConstructedWith($httpClient, $requestFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(\Akeneo\Pim\ApiClient\Client\HttpClient::class);
        $this->shouldImplement(HttpClientInterface::class);
    }

    function it_sends_a_successful_request(
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

    function it_throws_an_exception_when_failing_request(
        $requestFactory,
        $httpClient,
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $requestFactory->createRequest(
            'POST',
            'http://akeneo.com/api/rest/v1/products/foo',
            ['Content-Type' => 'application/json'],
            '{"identifier": "foo"}'
        )->willReturn($request);

        $httpClient->sendRequest($request)->willReturn($response);

        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 500, "message": "Internal error."}');
        $responseBody->rewind()->shouldBeCalled();

        $this
            ->shouldThrow(HttpException::class)
            ->during('sendRequest', [
                'POST',
                'http://akeneo.com/api/rest/v1/products/foo',
                ['Content-Type' => 'application/json'],
                '{"identifier": "foo"}'
            ]);
    }
}
