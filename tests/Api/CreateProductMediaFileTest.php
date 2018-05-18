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

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_POST]['product'], json_encode($productInfos));
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_REQUEST_URI], '/'. ProductMediaFileApi::MEDIA_FILES_URI);

        Assert::assertSame(201, $response->getStatusCode());
        Assert::assertSame('', $response->getBody()->getContents());
        Assert::assertSame('f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png', $this->extractCodeFromCreationResponse($response));
    }
}
