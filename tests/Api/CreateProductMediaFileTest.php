<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class CreateProductMediaFileTest extends ApiTestCase
{
    public function test_create_media_file()
    {
        $mediaFileURI = $this->server->getServerRoot(). '/' . ProductMediaFileApi::MEDIA_FILES_URI.'/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png';
        $this->server->setResponseOfPath(
            '/'. ProductMediaFileApi::MEDIA_FILES_URI,
            new ResponseStack(
                new Response('', ['Location' => $mediaFileURI], 201)
            )
        );

        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFile = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $productInfos = [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ];

        $response = $api->create($mediaFile, $productInfos);

        $lastRequest = $this->server->getLastRequest()->jsonSerialize();
        Assert::assertSame($lastRequest[RequestInfo::JSON_KEY_POST]['product'], json_encode($productInfos));
        Assert::assertNotEmpty($lastRequest[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame($lastRequest[RequestInfo::JSON_KEY_FILES]['file']['name'], 'akeneo.png');
        Assert::assertSame($lastRequest[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/png');
        Assert::assertSame($lastRequest[RequestInfo::JSON_KEY_FILES]['file']['size'], 8073);

        Assert::assertSame('f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png', $response);
    }

    public function test_get_created_media_file_location_regardless_of_the_header_case()
    {
        $mediaFileURI = $this->server->getServerRoot(
            ) . '/' . ProductMediaFileApi::MEDIA_FILES_URI . '/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png';
        $this->server->setResponseOfPath(
            '/' . ProductMediaFileApi::MEDIA_FILES_URI,
            new ResponseStack(
                new Response('', ['LOcaTiON' => $mediaFileURI], 201)
            )
        );

        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFile = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $productInfos = [
            'identifier' => 'medium_boot',
            'attribute' => 'side_view',
            'scope' => null,
            'locale' => null,
        ];

        $response = $api->create($mediaFile, $productInfos);
        Assert::assertSame('f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png', $response);
    }
}
