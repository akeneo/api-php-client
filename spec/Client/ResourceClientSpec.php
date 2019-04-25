<?php

namespace spec\Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Client\HttpClientInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClient;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\MultipartStream\MultipartStreamBuilder;
use Akeneo\Pim\ApiClient\Stream\MultipartStreamBuilderFactory;
use Akeneo\Pim\ApiClient\Routing\UriGeneratorInterface;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponseFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceClientSpec extends ObjectBehavior
{
    function let(
        HttpClientInterface $httpClient,
        UriGeneratorInterface $uriGenerator,
        MultipartStreamBuilderFactory $multipartStreamBuilderFactory,
        UpsertResourceListResponseFactory $responseFactory
    ) {
        $this->beConstructedWith($httpClient, $uriGenerator, $multipartStreamBuilderFactory, $responseFactory);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ResourceClientInterface::class);
        $this->shouldHaveType(ResourceClient::class);
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

    function it_upserts_a_resource(
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
            ->upsertResource(
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

    function it_upserts_a_list_of_streamed_resources_from_an_array(
        $httpClient,
        $uriGenerator,
        $responseFactory,
        StreamInterface $responseBodyStream,
        UpsertResourceListResponse $listResponse,
        ResponseInterface $response
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/categories';

        $uriGenerator
            ->generate('api/rest/v1/categories', [])
            ->willReturn($uri);

        $body =
<<<JSON
{"code":"category_1"}
{"code":"category_2"}
{"code":"category_3"}
{"code":"category_4"}
JSON;

        $httpClient
            ->sendRequest('PATCH', $uri, ['Content-Type' => 'application/vnd.akeneo.collection+json'], $body)
            ->shouldBeCalled()
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBodyStream);

        $responseFactory->create($responseBodyStream)->willReturn($listResponse);

        $this
            ->upsertStreamResourceList(
                'api/rest/v1/categories',
                [],
                [
                    ['code'=> 'category_1'],
                    ['code'=> 'category_2'],
                    ['code'=> 'category_3'],
                    ['code'=> 'category_4'],
                ]
            )
            ->shouldReturn($listResponse);
    }

    function it_upserts_a_list_of_streamed_resources_from_an_stream(
        $httpClient,
        $uriGenerator,
        $responseFactory,
        StreamInterface $responseBodyStream,
        StreamInterface $resourcesStream,
        UpsertResourceListResponse $listResponse,
        ResponseInterface $response
    )
    {
        $uri = 'http://akeneo.com/api/rest/v1/categories';

        $uriGenerator
            ->generate('api/rest/v1/categories', [])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('PATCH', $uri, ['Content-Type' => 'application/vnd.akeneo.collection+json'], $resourcesStream)
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBodyStream);

        $responseFactory->create($responseBodyStream)->willReturn($listResponse);

        $this
            ->upsertStreamResourceList('api/rest/v1/categories', [], $resourcesStream)
            ->shouldReturn($listResponse);
    }

    function it_upserts_a_list_of_json_resources(
        HttpClient $httpClient,
        UriGeneratorInterface $uriGenerator,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/reference-entities/designer/records';

        $uriGenerator
            ->generate('api/rest/v1/reference-entities/%s/records', ['designer'])
            ->willReturn($uri);

        $body = <<<JSON
[{"code":"designer_1"},{"code":"designer_2"},{"code":"designer_3"}]
JSON;

        $httpClient
            ->sendRequest('PATCH', $uri, ['Content-Type' => 'application/json'], $body)
            ->willReturn($response);

        $response
            ->getBody()
            ->willReturn($responseBody);

        $upsertResponse = <<<JSON
        [
          {
            "code": "designer_1",
            "status_code": 204
          },
          {
            "code": "designer_2",
            "status_code": 204
          },
          {
            "code": "designer_3",
            "status_code": 201
          }
        ]
JSON;

        $responseBody
            ->getContents()
            ->willReturn($upsertResponse);

        $this->upsertJsonResourceList('api/rest/v1/reference-entities/%s/records', ['designer'], [
            ['code' => 'designer_1'],
            ['code' => 'designer_2'],
            ['code' => 'designer_3'],
        ])->shouldReturn([
            [
                'code' => 'designer_1',
                'status_code' =>204
            ],
            [
                'code' => 'designer_2',
                'status_code' =>204
            ],
            [
                'code' => 'designer_3',
                'status_code' =>201
            ]
        ]);
    }

    function it_throws_an_exception_if_limit_is_defined_in_additional_parameters_to_get_resources()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters'))
            ->during('getResources', ['', [], null, null, ['limit' => null]]);
    }

    function it_throws_an_exception_if_with_count_is_defined_in_additional_parameters_to_get_resources()
    {
        $this
            ->shouldthrow(new InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters'))
            ->during('getResources', ['', [], null, null, ['with_count' => null]]);
    }

    function it_throws_an_exception_if_resources_is_not_an_array_and_not_a_stream_when_upserting_a_list_of_resources()
    {
        $this
            ->shouldthrow(new InvalidArgumentException('The parameter "resources" must be an array or an instance of StreamInterface.'))
            ->during('upsertStreamResourceList', ['api/rest/v1/categories', [], 'foo']);
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
            ->shouldReturn($response);
    }

    function it_throws_an_exception_if_a_request_part_is_invalid_when_creating_a_multipart_resource(
        $multipartStreamBuilderFactory,
        MultipartStreamBuilder $multipartStreamBuilder
    ) {
        $multipartStreamBuilderFactory->create()->willReturn($multipartStreamBuilder);

        $this
            ->shouldThrow(new InvalidArgumentException('The keys "name" and "contents" must be defined for each request part'))
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
            ->shouldThrow(new InvalidArgumentException('The keys "name" and "contents" must be defined for each request part'))
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

    function it_deletes_a_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'api/rest/v1/products/foo';

        $uriGenerator
            ->generate('api/rest/v1/products/%s', ['foo'])
            ->willReturn($uri);

        $httpClient
            ->sendRequest('DELETE', $uri)
            ->willReturn($response);

        $response
            ->getStatusCode()
            ->willReturn(204);

        $this
            ->deleteResource('api/rest/v1/products/%s', ['foo'])
            ->shouldReturn(204);
    }

    function it_gets_a_streamed_resource(
        $httpClient,
        $uriGenerator,
        ResponseInterface $response
    ) {
        $uri = 'http://akeneo.com/api/rest/v1/media-files/42.jpg/download';

        $uriGenerator
            ->generate('api/rest/v1/media-files/%s/download', ['42.jpg'])
            ->willReturn($uri);

        $httpClient->sendRequest('GET', $uri, ['Accept' => '*/*'])->willReturn($response);

        $this->getStreamedResource('api/rest/v1/media-files/%s/download', ['42.jpg'])->shouldReturn($response);
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
