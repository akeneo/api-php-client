<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\Pagination\Page;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, UriGeneratorInterface $uriGenerator)
    {
        $this->beConstructedWith($httpClient, $uriGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Akeneo\Pim\Client\ResourceClientInterface');
        $this->shouldHaveType('Akeneo\Pim\Client\ResourceClient');
    }

    function it_gets_resource($httpClient, $uriGenerator, ResponseInterface $response, StreamInterface $responseBody)
    {
        $uri = 'http://akeneo.com/api/rest/v1/categories/winter_collection';
        $resource =
<<<JSON
{
    "code": "winter_collection",
    "parent": null,
    "labels": {
        "en_US": "Winter collection",
        "fr_FR": "Collection hiver"
    }
}
JSON;

        $uriGenerator
            ->generate('api/rest/v1/categories/%s', ['winter_collection'], [])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn($resource);

        $this->getResource('api/rest/v1/categories/%s', ['winter_collection'], [])->shouldReturn([
            'code' => 'winter_collection',
            'parent' => null,
            'labels' => [
                'en_US' => 'Winter collection',
                'fr_FR' => 'Collection hiver',
            ],
        ]);
    }

    function it_returns_a_page_when_requesting_a_list_of_resources(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories?limit=10&with_count=15&foo=bar';
        $resources = $this->getSampleOfResources();

        $uriGenerator
            ->generate('api/rest/v1/categories', [], ['foo' => 'bar', 'limit' => 10, 'with_count' => true])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn(json_encode($resources));

        $this->getResources('api/rest/v1/categories', [], 10, true, ['foo' => 'bar'])->shouldReturn($resources);
    }

    function it_returns_a_list_of_resources_without_limit_and_count(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories?foo=bar';
        $resources = $this->getSampleOfResources();

        $uriGenerator
            ->generate('api/rest/v1/categories', [], ['foo' => 'bar'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('GET', $uri, ['Accept' => '*/*'])
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->willReturn(json_encode($resources));

        $this->getResources('api/rest/v1/categories', [], null, null, ['foo' => 'bar'])->shouldReturn($resources);
    }

    function it_creates_a_resource(
        $httpClient,
        $uriGenerator
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories';

        $uriGenerator
            ->generate('api/rest/v1/categories', [])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('POST', $uri, ['Content-Type' => 'application/json'], '{"code":"master"}')
            ->shouldBeCalled();

        $this->createResource(
            'api/rest/v1/categories',
            [],
            [
                '_links' => [
                    'self' => [
                        'href' => 'http://akeneo.com/self',
                    ],
                ],
                'code'   => 'master',
            ]
        );
    }

    function it_updates_partially_a_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories/master';

        $uriGenerator
            ->generate('api/rest/v1/categories/%s', ['master'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('PATCH', $uri, ['Content-Type' => 'application/json'], '{"parent":"foo"}')
            ->willReturn($response);

        $response
            ->getStatusCode()
            ->willReturn(201);

        $this
            ->partialUpdateResource(
                'api/rest/v1/categories/%s',
                ['master'],
                [
                    '_links' => [
                        'self' => [
                            'href' => 'http://akeneo.com/self',
                        ],
                    ],
                    'parent' => 'foo'
                ]
            )
            ->shouldReturn(201);
    }

    function it_throws_an_exception_if_limit_is_defined_in_additional_parameters_to_get_resources()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('getResources', ['', [], null, null, ['limit' => null]]);
    }

    function it_throws_an_exception_if_with_count_is_defined_in_additional_parameters_to_get_resources()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('getResources', ['', [], null, null, ['with_count' => null]]);
    }

    protected function getSampleOfResources()
    {
        return [
            '_links'      => [
                'self'     => [
                    'href' => 'http://akeneo.com/self',
                ],
                'first'    => [
                    'href' => 'http://akeneo.com/first',
                ],
                'previous' => [
                    'href' => 'http://akeneo.com/previous',
                ],
                'next'     => [
                    'href' => 'http://akeneo.com/next',
                ],
            ],
            'items_count' => 10,
            '_embedded'   => [
                'items' => [
                    ['identifier' => 'foo'],
                    ['identifier' => 'bar'],
                ],
            ],
        ];
    }
}
