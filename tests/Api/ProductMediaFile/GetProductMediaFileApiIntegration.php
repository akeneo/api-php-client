<?php

namespace Akeneo\Pim\tests\Api\ProductMediaFile;

use Akeneo\Pim\Api\ProductMediaFileApi;
use Akeneo\Pim\tests\Api\ApiTestCase;

class GetProductMediaFileApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $code = $api->listPerPage(1)->getItems()[0]['code'];
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $mediaFile = $api->get($code);
        $this->assertInternalType('array', $mediaFile);

        $expectedMediaFile = [
            'code'              => $code,
            'original_filename' => 'Ziggy-certification.jpg',
            'mime_type'         => 'image/jpeg',
            'size'              => 10513,
            'extension'         => 'jpg',
            '_links'            => [
                'download' => [
                    'href' => sprintf('%s/api/rest/v1/media-files/%s/download', $baseUri, $code),
                ],
            ],
        ];

        $this->assertSameContent($expectedMediaFile, $mediaFile);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getProductMediaFileApi();

        $api->get('b/b/6/c/bb6ce0ef18bfa15d9e9fb4e3b26ce7064ac80b63_unknown_media_file.jpg');
    }
}
