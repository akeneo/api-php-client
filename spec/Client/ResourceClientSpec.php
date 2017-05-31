<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\MultipartStream\MultipartStreamBuilderFactory;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient, UriGeneratorInterface $uriGenerator, MultipartStreamBuilderFactory $multipartStreamBuilderFactory)
    {
        $this->beConstructedWith($httpClient, $uriGenerator, $multipartStreamBuilderFactory);
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
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories';

        $uriGenerator
            ->generate('api/rest/v1/categories', [])
            ->willReturn($uri);

        $response
            ->getStatusCode()
            ->willReturn(201);

        $httpClient
            ->sendRequest('POST', $uri, ['Content-Type' => 'application/json'], '{"code":"master"}')
            ->willReturn($response);

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

    function it_creates_a_multipart_resource(
        $httpClient,
        $uriGenerator,
        $multipartStreamBuilderFactory,
        ResponseInterface $response,
        MultipartStreamBuilder $multipartStreamBuilder
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/media-files';
        $boundary = '59282643a51ca1.81601629';
        $product = '{"identifier":"foo","attribute":"picture","scope":"e-commerce","locale":"en_US"}';
        $fileResource = '42';
        $multipartStream = 'stream';
        $requestParts = [
            [
                'name' => 'product',
                'contents' => $product,
            ],
            [
                'name' => 'file',
                'contents' => $fileResource,
            ]
        ];

        $uriGenerator->generate('api/rest/v1/media-files', [])->willReturn($uri);

        $multipartStreamBuilderFactory->create()->willReturn($multipartStreamBuilder);

        $multipartStreamBuilder->build()->willReturn($multipartStream);
        $multipartStreamBuilder->addResource('product', $product, [])->shouldBeCalled();
        $multipartStreamBuilder->addResource('file', $fileResource, [])->shouldBeCalled();
        $multipartStreamBuilder->getBoundary()->willReturn($boundary);

        $headers = ['Content-Type' => sprintf('multipart/form-data; boundary="%s"', $boundary)];

        $response->getStatusCode()->willReturn(201);

        $httpClient
            ->sendRequest('POST', $uri, $headers, $multipartStream)
            ->willReturn($response);

        $this
            ->createMultipartResource('api/rest/v1/media-files', [], $requestParts)
            ->shouldReturn(201);
    }

    function it_throws_an_exception_if_a_request_part_is_invalid_when_creating_a_multipart_resource(
        $multipartStreamBuilderFactory,
        MultipartStreamBuilder $multipartStreamBuilder
    ) {
        $multipartStreamBuilderFactory->create()->willReturn($multipartStreamBuilder);

        $this
            ->shouldThrow(new \InvalidArgumentException('The keys "name" and "contents" must be defined for each request part'))
            ->during('createMultipartResource', [
                'api/rest/v1/media-files',
                [],
                [
                    [
                        'name' => 'product',
                        'contents' => 'foo',
                    ],
                    [
                        'name' => 'file',
                    ]
                ]
            ]);

        $this
            ->shouldThrow(new \InvalidArgumentException('The keys "name" and "contents" must be defined for each request part'))
            ->during('createMultipartResource', [
                'api/rest/v1/media-files',
                [],
                [
                    [
                        'name' => null,
                        'contents' => 'foo',
                    ]
                ]
            ]);
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
