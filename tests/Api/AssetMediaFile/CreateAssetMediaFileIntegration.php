<?php

namespace Akeneo\Pim\ApiClient\tests\Api\AssetFamily;

use Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class CreateAssetMediaFileIntegration extends ApiTestCase
{
    public function test_create_asset_media_file()
    {
        $this->server->setResponseOfPath(
            '/' . AssetMediaFileApi::MEDIA_FILE_CREATE_URI,
            new ResponseStack(
                new Response('', ['Asset-media-file-code' => 'my-asset-media-code'], 201)
            )
        );
        $mediaFile = realpath(__DIR__ . '/../../fixtures/unicorn.png');
        $response = $this->createClientByPassword()->getAssetMediaFileApi()->create($mediaFile);

        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame(
            'unicorn.png',
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name']
        );
        Assert::assertSame(
            'image/png',
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type']
        );
        Assert::assertSame(
            11255,
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size']
        );
        Assert::assertSame('my-asset-media-code', $response);
    }

    public function test_get_asset_media_file_code_regardless_of_the_header_case()
    {
        $this->server->setResponseOfPath(
            '/' . AssetMediaFileApi::MEDIA_FILE_CREATE_URI,
            new ResponseStack(
                new Response('', ['Asset-Media-File-Code' => 'my-asset-media-code'], 201)
            )
        );
        $mediaFile = realpath(__DIR__ . '/../../fixtures/unicorn.png');
        $response = $this->createClientByPassword()->getAssetMediaFileApi()->create($mediaFile);

        Assert::assertSame('my-asset-media-code', $response);
    }
}
