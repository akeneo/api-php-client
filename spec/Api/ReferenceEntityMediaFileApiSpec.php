<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class ReferenceEntityMediaFileApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient, FileSystemInterface $fileSystem)
    {
        $this->beConstructedWith($resourceClient, $fileSystem);
    }

    function it_is_a_reference_entity_media_file_api()
    {
        $this->shouldImplement(ReferenceEntityMediaFileApiInterface::class);
    }

    function it_downloads_a_reference_entity_media_file(ResourceClientInterface $resourceClient, ResponseInterface $response)
    {
        $resourceClient
            ->getStreamedResource(ReferenceEntityMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, ['images/starck.jpg'])
            ->willReturn($response);

        $this->download('images/starck.jpg')->shouldReturn($response);
    }

    function it_creates_a_reference_entity_media_file(
        ResourceClientInterface $resourceClient,
        FileSystemInterface $fileSystem,
        ResponseInterface $response
    ) {
        $fileResource = fopen('php://memory', 'r');
        $fileSystem->getResourceFromPath(Argument::any())->shouldNotBeCalled();

        $requestParts = [
            [
                'name'     => 'file',
                'contents' => $fileResource,
            ]
        ];

        $response->getHeader('Reference-entities-media-file-code')->shouldBeCalled()->willReturn(
            [
                '0/f/b/f/0fbffddc95c3d610b39e3f3797b14c6c33e98a4f_starck.jpg'
            ]
        );

        $resourceClient
            ->createMultipartResource(ReferenceEntityMediaFileApi::MEDIA_FILE_CREATE_URI, [], $requestParts)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->create($fileResource)
            ->shouldReturn('0/f/b/f/0fbffddc95c3d610b39e3f3797b14c6c33e98a4f_starck.jpg');
    }
}
