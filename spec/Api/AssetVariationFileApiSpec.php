<?php

namespace spec\Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\AssetVariationFileApi;
use Akeneo\Pim\ApiClient\Api\AssetVariationFileApiInterface;
use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class AssetVariationFileApiSpec extends ObjectBehavior
{
    public function let(ResourceClientInterface $resourceClient, FileSystemInterface $fileSystem)
    {
        $this->beConstructedWith($resourceClient, $fileSystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AssetVariationFileApi::class);
        $this->shouldImplement(AssetVariationFileApiInterface::class);
    }

    function it_gets_a_localizable_asset_variation_file($resourceClient)
    {
        $assetVariationFile = [
            'code'   => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_ziggy_en_US_mobile.jpg',
            'locale' => 'en_US',
            '_link'  => [
                'download' => [
                    'href' => 'http://akeneo-ped-master.local/api/rest/v1/assets/ziggy/variation-files/mobile/en_US/download'
                ]
            ],
        ];

        $resourceClient
            ->getResource(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, ['ziggy', 'mobile', 'en_US'])
            ->willReturn($assetVariationFile);

        $this->getFromLocalizableAsset('ziggy', 'mobile', 'en_US')->shouldReturn($assetVariationFile);
    }

    function it_gets_a_not_localizable_asset_variation_file($resourceClient)
    {
        $assetVariationFile = [
            'code'   => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_ziggy_mobile.jpg',
            'locale' => 'en_US',
            '_link'  => [
                'download' => [
                    'href' => 'http://akeneo-ped-master.local/api/rest/v1/assets/ziggy/variation-files/mobile/no-locale/download'
                ]
            ],
        ];

        $resourceClient
            ->getResource(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, ['ziggy', 'mobile', AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE])
            ->willReturn($assetVariationFile);

        $this->getFromNotLocalizableAsset('ziggy', 'mobile')->shouldReturn($assetVariationFile);
    }

    function it_uploads_a_localizable_asset_variation_file(
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
            ->createMultipartResource(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, ['ziggy', 'mobile', 'en_US'], $requestParts)
            ->willReturn($response);

        $this->uploadForLocalizableAsset('images/ziggy.png', 'ziggy', 'mobile', 'en_US')->shouldReturn(201);
    }

    function it_uploads_a_not_localizable_asset_variation_file(
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
            ->createMultipartResource(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, ['ziggy', 'mobile', AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE], $requestParts)
            ->willReturn($response);

        $this->uploadForNotLocalizableAsset('images/ziggy.png', 'ziggy', 'mobile')->shouldReturn(201);
    }

    function it_uploads_an_asset_variation_file_from_a_file_resource(
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
            ->createMultipartResource(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, ['ziggy', 'mobile', 'en_US'], $requestParts)
            ->willReturn($response);

        $this->uploadForLocalizableAsset($fileResource, 'ziggy', 'mobile', 'en_US')->shouldReturn(201);
    }
}
