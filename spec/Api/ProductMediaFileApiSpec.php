<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\ListableResourceInterface;
use Akeneo\Pim\Api\ProductMediaFileApi;
use Akeneo\Pim\Api\MediaFileApiInterface;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ProductMediaFileApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductMediaFileApi::class);
        $this->shouldImplement(MediaFileApiInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
    }

    function it_returns_a_media_file($resourceClient)
    {
        $mediaFileCode = '3/e/42.jpg';
        $mediaFile = [
            'code'              => '3/e/42.jpg',
            'original_filename' => '42.jpg',
            'mime_type'         => 'image/jpeg',
        ];

        $resourceClient
            ->getResource(ProductMediaFileApi::MEDIA_FILE_URI, [$mediaFileCode])
            ->willReturn($mediaFile);

        $this->get($mediaFileCode)->shouldReturn($mediaFile);
    }

    function it_returns_a_list_of_media_files_with_default_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ProductMediaFileApi::MEDIA_FILES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage()->shouldReturn($page);
    }

    function it_returns_a_list_of_media_files_with_limit_and_count($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ProductMediaFileApi::MEDIA_FILES_URI, [], 10, true, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, true)->shouldReturn($page);
    }

    function it_returns_a_cursor_on_the_list_of_media_files(
        $resourceClient,
        $pageFactory,
        $cursorFactory,
        PageInterface $page,
        ResourceCursorInterface $cursor
    ) {
        $resourceClient
            ->getResources(ProductMediaFileApi::MEDIA_FILES_URI, [], 10, false, [])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $cursorFactory->createCursor(10, $page)->willReturn($cursor);

        $this->all(10, [])->shouldReturn($cursor);
    }

    function it_returns_a_list_of_media_files_with_additional_query_parameters($resourceClient, $pageFactory, PageInterface $page)
    {
        $resourceClient
            ->getResources(ProductMediaFileApi::MEDIA_FILES_URI, [], null, null, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(null, null, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_media_file($resourceClient, ResponseInterface $response)
    {
        $fileResource = fopen('php://stdin', 'r');
        $product = [
            'identifier' => 'foo',
            'attribute'  => 'picture',
            'scope'      => 'e-commerce',
            'locale'     => 'en_US',
        ];

        $requestParts = [
            [
                'name'     => 'product',
                'contents' => json_encode($product),
            ],
            [
                'name'     => 'file',
                'contents' => $fileResource,
            ]
        ];

        $response->getHeaders()->willReturn(['Location' => [
            'http://localhost/api/rest/v1/media-files/1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png'
        ]]);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->willReturn($response);

        $this->create($fileResource, $product)
            ->shouldReturn('1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png');
    }

    function it_throws_an_exception_if_the_file_is_unreadable_when_creating_a_media_file()
    {
        $this
            ->shouldThrow(new \RuntimeException('The file "/foo.bar" could not be read.'))
            ->during('create', [
                '/foo.bar',
                [
                    'identifier' => 'foo',
                    'attribute'  => 'picture',
                    'scope'      => 'e-commerce',
                    'locale'     => 'en_US',
                ]
            ]);
    }

    function it_throws_an_exception_if_the_response_does_not_contain_the_uri_of_the_created_media_file($resourceClient, ResponseInterface $response)
    {
        $fileResource = fopen('php://stdin', 'r');
        $product = [
            'identifier' => 'foo',
            'attribute'  => 'picture',
            'scope'      => 'e-commerce',
            'locale'     => 'en_US',
        ];

        $requestParts = [
            [
                'name'     => 'product',
                'contents' => json_encode($product),
            ],
            [
                'name'     => 'file',
                'contents' => $fileResource,
            ]
        ];

        $response->getHeaders()->willReturn(['Location' => '']);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_PATH, [], $requestParts)
            ->willReturn($response);

        $this
            ->shouldThrow(new \RuntimeException('The response does not contain the URI of the created media-file.'))
            ->during('create', [$fileResource, $product]);
    }

    function it_throws_an_exception_if_the_uri_of_the_created_media_file_is_invalid($resourceClient, ResponseInterface $response)
    {
        $fileResource = fopen('php://stdin', 'r');
        $product = [
            'identifier' => 'foo',
            'attribute'  => 'picture',
            'scope'      => 'e-commerce',
            'locale'     => 'en_US',
        ];

        $requestParts = [
            [
                'name'     => 'product',
                'contents' => json_encode($product),
            ],
            [
                'name'     => 'file',
                'contents' => $fileResource,
            ]
        ];

        $response->getHeaders()->willReturn(['Location' => ['http://localhost/api/rest/v1/products/foo']]);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_PATH, [], $requestParts)
            ->willReturn($response);

        $this
            ->shouldThrow(new \RuntimeException('Unable to find the code in the URI of the created media-file.'))
            ->during('create', [$fileResource, $product]);
    }

    function it_downloads_a_media_file($resourceClient, StreamInterface $streamBody)
    {
        $resourceClient
            ->getStreamedResource(ProductMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, ['42.jpg'])
            ->willReturn($streamBody);

        $this->download('42.jpg')->shouldReturn($streamBody);
    }
}
