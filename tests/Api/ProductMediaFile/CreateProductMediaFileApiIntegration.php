<?php

namespace Akeneo\Pim\tests\Api\ProductMediaFile;

use Akeneo\Pim\tests\Api\ApiTestCase;
use Akeneo\Pim\tests\MediaSanitizer;

class CreateProductMediaFileApiIntegration extends ApiTestCase
{
    public function testCreateSuccessful()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];
        $mediaFile = realpath(__DIR__ . '/../../fixtures/akeneo.png');

        $response = $api->create($mediaFile, [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);

        $this->assertSame(201, $response);

        $mediaFiles = $api->listPerPage(10)->getItems();

        $this->assertCount(5, $mediaFiles);

        $expectedMediaFile = [
            '_links' => [
                'self'     => [
                    'href' => $baseUri . '/api/rest/v1/media-files/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png',
                ],
                'download' => [
                    'href' => $baseUri . '/api/rest/v1/media-files/f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png/download',
                ],
            ],
            'code'              => 'f/b/0/6/fb068ccc9e3c5609d73c28d852812ba5faeeab28_akeneo.png',
            'original_filename' => 'akeneo.png',
            'mime_type'         => 'image/png',
            'size'              => 8073,
            'extension'         => 'png',
        ];

        $expectedMediaFile = $this->sanitizeMediaFile($expectedMediaFile);
        $mediaFiles[4] = $this->sanitizeMediaFile($mediaFiles[4]);

        $this->assertSameContent($expectedMediaFile, $mediaFiles[4]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnExistingMediaFile()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFile = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $api->create($mediaFile, [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);

        $api->create($mediaFile, [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testCreateWithAnInvalidRequest()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFile = realpath(__DIR__ . '/../fixtures/akeneo.png');

        $api->create($mediaFile, [
            'identifier' => 'unknown_product',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);
    }

    /**
     * Sanitize the code and links of a media file, because the code is generated randomly.
     *
     * @param array $mediaFile
     *
     * @return array
     */
    protected function sanitizeMediaFile(array $mediaFile)
    {
        $mediaFile['code'] = MediaSanitizer::sanitize($mediaFile['code']);
        $mediaFile['_links']['self']['href'] = MediaSanitizer::sanitize($mediaFile['_links']['self']['href']);
        $mediaFile['_links']['download']['href'] = MediaSanitizer::sanitize($mediaFile['_links']['download']['href']);

        return $mediaFile;
    }
}
