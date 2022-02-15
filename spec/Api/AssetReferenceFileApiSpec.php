<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApi;
use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\UploadAssetReferenceFileErrorException;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class AssetReferenceFileApiSpec extends ObjectBehavior
{
    public function let(ResourceClientInterface $resourceClient, FileSystemInterface $fileSystem)
    {
        $this->beConstructedWith($resourceClient, $fileSystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AssetReferenceFileApi::class);
        $this->shouldImplement(AssetReferenceFileApiInterface::class);
    }

    function it_gets_a_localizable_asset_reference_file($resourceClient)
    {
        $assetReferenceFile = [
            'code'   => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_Ziggy.jpg',
            'locale' => 'en_US',
            '_link'  => [
                'download' => [
                    'href' => 'http://akeneo-ped-master.local/api/rest/v1/assets/ziggy/reference-files/en_US/download'
                ]
            ],
        ];

        $resourceClient
            ->getResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', 'en_US'])
            ->willReturn($assetReferenceFile);

        $this->getFromLocalizableAsset('ziggy', 'en_US')->shouldReturn($assetReferenceFile);
    }

    function it_gets_a_not_localizable_asset_reference_file($resourceClient)
    {
        $assetReferenceFile = [
            'code'   => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_Ziggy.jpg',
            'locale' => 'en_US',
            '_link'  => [
                'download' => [
                    'href' => 'http://akeneo-ped-master.local/api/rest/v1/assets/ziggy/reference-files/no-locale/download'
                ]
            ],
        ];

        $resourceClient
            ->getResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', AssetReferenceFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE])
            ->willReturn($assetReferenceFile);

        $this->getFromNotLocalizableAsset('ziggy')->shouldReturn($assetReferenceFile);
    }

    function it_uploads_a_localizable_asset_reference_file(
        $resourceClient,
        $fileSystem,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $fileSystem->getResourceFromPath('images/ziggy.png')->willReturn('fileResource');

        $requestParts = [[
            'name' => 'file',
            'contents' => 'fileResource',
        ]];

        $response->getStatusCode()->willReturn(201);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('');

        $resourceClient
            ->createMultipartResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', 'en_US'], $requestParts)
            ->willReturn($response);

        $this->uploadForLocalizableAsset('images/ziggy.png', 'ziggy', 'en_US')->shouldReturn(201);
    }

    function it_uploads_a_not_localizable_asset_reference_file(
        $resourceClient,
        $fileSystem,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $fileSystem->getResourceFromPath('images/ziggy.png')->willReturn('fileResource');

        $requestParts = [[
            'name' => 'file',
            'contents' => 'fileResource',
        ]];

        $response->getStatusCode()->willReturn(201);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('');

        $resourceClient
            ->createMultipartResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', AssetReferenceFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE], $requestParts)
            ->willReturn($response);

        $this->uploadForNotLocalizableAsset('images/ziggy.png', 'ziggy')->shouldReturn(201);
    }

    function it_uploads_an_asset_reference_file_from_a_file_resource(
        $resourceClient,
        $fileSystem,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $fileSystem->getResourceFromPath(Argument::any())->shouldNotBeCalled();

        $fileResource = fopen('php://stdin', 'r');

        $requestParts = [[
            'name' => 'file',
            'contents' => $fileResource,
        ]];

        $response->getStatusCode()->willReturn(201);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('');

        $resourceClient
            ->createMultipartResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', 'en_US'], $requestParts)
            ->willReturn($response);

        $this->uploadForLocalizableAsset($fileResource, 'ziggy', 'en_US')->shouldReturn(201);
    }

    function it_throws_an_exception_if_the_upload_response_contains_errors(
        $resourceClient,
        $fileSystem,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $fileSystem->getResourceFromPath('images/ziggy.png')->willReturn('fileResource');

        $requestParts = [[
            'name' => 'file',
            'contents' => 'fileResource',
        ]];

        $response->getStatusCode()->willReturn(201);
        $response->getBody()->willReturn($responseBody);

        $responseContent =
<<<JSON
{
  "message": "Some variation files were not generated properly.",
  "errors": [
    {
      "message": "Impossible to \"resize\" the image \"/tmp/pim/file_storage/4/2/5/1/ziggy-en_US-ecommerce.png\" with a width bigger than the original.",
      "scope": "ecommerce",
      "locale": "en_US"
    },
    {
      "message": "Impossible to \"resize\" the image \"/tmp/pim/file_storage/4/2/5/1/ziggy-en_US-mobile.png\" with a height bigger than the original.",
      "scope": "mobile",
      "locale": "en_US"
    }
  ]
}
JSON;

        $responseBody->getContents()->willReturn($responseContent);

        $resourceClient
            ->createMultipartResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, ['ziggy', 'en_US'], $requestParts)
            ->willReturn($response);

        $this->shouldThrow(new UploadAssetReferenceFileErrorException('Some variation files were not generated properly.', [
            [
                'message' => 'Impossible to "resize" the image "/tmp/pim/file_storage/4/2/5/1/ziggy-en_US-ecommerce.png" with a width bigger than the original.',
                'scope' => 'ecommerce',
                'locale' => 'en_US'
            ],
            [
                'message' => 'Impossible to "resize" the image "/tmp/pim/file_storage/4/2/5/1/ziggy-en_US-mobile.png" with a height bigger than the original.',
                'scope' => 'mobile',
                'locale' => 'en_US'
            ]
        ]))
            ->during('uploadForLocalizableAsset', ['images/ziggy.png', 'ziggy', 'en_US']);
    }

    function it_downloads_a_localizable_asset_reference_file($resourceClient, ResponseInterface $response, StreamInterface $streamBody)
    {
        $resourceClient
            ->getStreamedResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_DOWNLOAD_URI, ['ziggy', 'en_US'])
            ->willReturn($response);

        $this->downloadFromLocalizableAsset('ziggy', 'en_US')->shouldReturn($response);
    }

    function it_downloads_a_not_localizable_asset_reference_file($resourceClient, ResponseInterface $response, StreamInterface $streamBody)
    {
        $resourceClient
            ->getStreamedResource(AssetReferenceFileApi::ASSET_REFERENCE_FILE_DOWNLOAD_URI, ['ziggy', AssetReferenceFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE])
            ->willReturn($response);

        $this->downloadFromNotLocalizableAsset('ziggy')->shouldReturn($response);
    }
}
