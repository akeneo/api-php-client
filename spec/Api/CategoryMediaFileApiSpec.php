<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\CategoryMediaFileApi;
use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CategoryMediaFileApiSpec extends ObjectBehavior
{
    function let(
        ResourceClientInterface $resourceClient,
    ) {
        $this->beConstructedWith($resourceClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryMediaFileApi::class);
        $this->shouldImplement(DownloadableResourceInterface::class);
    }

    function it_downloads_a_media_file($resourceClient, ResponseInterface $response, StreamInterface $streamBody)
    {
        $resourceClient
            ->getStreamedResource(CategoryMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, ['42.jpg'])
            ->willReturn($response);

        $this->download('42.jpg')->shouldReturn($response);
    }
}
