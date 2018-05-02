<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use Psr\Http\Message\StreamInterface;

class DownloadProductMediaFileTest extends ApiTestCase
{
    public function test_download_media_file()
    {
        $expectedMediaFile = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $this->server->setResponseOfPath(
            '/'. sprintf(ProductMediaFileApi::MEDIA_FILE_DOWNLOAD_URI, '/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png'),
            new ResponseStack(
                new Response(file_get_contents($expectedMediaFile), [], 201)
            )
        );

        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFile = $api->download('/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png');

        $this->assertInstanceOf(StreamInterface::class, $mediaFile);
        $this->assertSame(file_get_contents($expectedMediaFile), $mediaFile->getContents());
    }
}