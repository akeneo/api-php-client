<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\CategoryMediaFileApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class DownloadCategoryMediaFileTest extends ApiTestCase
{
    public function test_download_media_file()
    {
        $expectedMediaFilePath = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(CategoryMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, '/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png'),
            new ResponseStack(
                new Response(file_get_contents($expectedMediaFilePath), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getCategoryMediaFileApi();
        $mediaFile = $api->download('/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png');

        Assert::assertSame('GET', $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD]);
        Assert::assertInstanceOf(ResponseInterface::class, $mediaFile);
        Assert::assertSame(file_get_contents($expectedMediaFilePath), $mediaFile->getBody()->getContents());
    }
}
