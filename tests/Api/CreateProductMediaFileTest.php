<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
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

        $response = $api->create($mediaFile, [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);

        Assert::assertSame('f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png', $response);
    }
}
