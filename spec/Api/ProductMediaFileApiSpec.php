<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use Akeneo\Pim\ApiClient\Api\MediaFileApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ProductMediaFileApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory,
        FileSystemInterface $fileSystem
    ) {
        $this->beConstructedWith($resourceClient, $pageFactory, $cursorFactory, $fileSystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductMediaFileApi::class);
        $this->shouldImplement(MediaFileApiInterface::class);
        $this->shouldImplement(GettableResourceInterface::class);
        $this->shouldImplement(ListableResourceInterface::class);
        $this->shouldImplement(DownloadableResourceInterface::class);
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
            ->getResources(ProductMediaFileApi::MEDIA_FILES_URI, [], 10, false, ['foo' => 'bar'])
            ->willReturn([]);

        $pageFactory->createPage([])->willReturn($page);

        $this->listPerPage(10, false, ['foo' => 'bar'])->shouldReturn($page);
    }

    function it_creates_a_media_file_from_a_path($resourceClient, $fileSystem, ResponseInterface $response)
    {
        $fileResource = fopen('php://stdin', 'r');
        $fileSystem->getResourceFromPath('/images/akeneo.png')->willReturn($fileResource);

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

        $response->hasHeader('location')->willReturn(true);

        $response->getHeader('location')->willReturn([
            'http://localhost/api/rest/v1/media-files/1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png'
        ]);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->willReturn($response);

        $this->create('/images/akeneo.png', $product)
            ->shouldReturn('1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png');
    }

    function it_creates_a_media_file_from_a_resource($resourceClient, $fileSystem, ResponseInterface $response)
    {
        $fileResource = fopen('php://stdin', 'r');
        $fileSystem->getResourceFromPath(Argument::any())->shouldNotBeCalled();

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

        $response->hasHeader('location')->willReturn(true);
        $response->getHeader('location')->willReturn([
            'http://localhost/api/rest/v1/media-files/1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png'
        ]);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->willReturn($response);

        $this->create($fileResource, $product)
            ->shouldReturn('1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png');
    }

    function it_creates_a_media_file_for_a_product_model($resourceClient, $fileSystem, ResponseInterface $response)
    {
        $fileResource = fopen('php://memory', 'r');
        $fileSystem->getResourceFromPath(Argument::any())->shouldNotBeCalled();

        $productModel = [
            'code'       => 'foo',
            'attribute'  => 'picture',
            'scope'      => 'e-commerce',
            'locale'     => 'en_US',
            'type'       => 'product_model',
        ];

        $requestProductModel = $productModel;
        unset($requestProductModel['type']);
        $requestParts = [
            [
                'name'     => 'product_model',
                'contents' => json_encode($requestProductModel),
            ],
            [
                'name'     => 'file',
                'contents' => $fileResource,
            ]
        ];

        $response->hasHeader('location')->willReturn(true);
        $response->getHeader('location')->willReturn([
            'http://localhost/api/rest/v1/media-files/1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png'
        ]);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->create($fileResource, $productModel)
            ->shouldReturn('1/e/e/d/1eed10f108bde68b279d6f903f17b4b053e9d89d_akeneo.png');
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

        $response->hasHeader('location')->willReturn(false);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->willReturn($response);

        $this
            ->shouldThrow(new RuntimeException('The response does not contain the URI of the created media-file.'))
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

        $response->hasHeader('location')->willReturn(true);
        $response->getHeader('location')->willReturn(['http://localhost/api/rest/v1/products/foo']);

        $resourceClient
            ->createMultipartResource(ProductMediaFileApi::MEDIA_FILES_URI, [], $requestParts)
            ->willReturn($response);

        $this
            ->shouldThrow(new RuntimeException('Unable to find the code in the URI of the created media-file.'))
            ->during('create', [$fileResource, $product]);
    }

    function it_downloads_a_media_file($resourceClient, ResponseInterface $response, StreamInterface $streamBody)
    {
        $resourceClient
            ->getStreamedResource(ProductMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, ['42.jpg'])
            ->willReturn($response);

        $this->download('42.jpg')->shouldReturn($response);
    }
}
