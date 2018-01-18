<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\ProductMediaFile;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;
use Akeneo\Pim\ApiClient\tests\MediaSanitizer;

class CreateMediaFileApiIntegration extends ApiTestCase
{
    public function testCreateSuccessful()
    {
        $client = $this->createClient();
        $api = $client->getProductMediaFileApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];
        $mediaFile = realpath(__DIR__ . '/../../../fixtures/akeneo.png');

        $response = $client->getAttributeApi()->create('product_model_media', [
            'type'                   => 'pim_catalog_image',
            'group'                  => 'media',
            'unique'                 => false,
            'useable_as_grid_filter' => false,
            'max_characters'         => null,
            'validation_rule'        => null,
            'validation_regexp'      => null,
            'localizable'            => true,
            'scopable'               => false,
            'labels'                 => [
                'en_US' => 'product model media',
            ],
        ]);

        $response = $client->getFamilyApi()->upsert('boots', [
            'attributes' => [
                'product_model_media',
                'color',
                'description',
                'manufacturer',
                'name',
                'price',
                'side_view',
                'size',
                'sku',
                'heel_color',
            ],
        ]);

        $initialCount = count($api->listPerPage(10)->getItems());
        $response = $api->create($mediaFile, [
            'code'       => 'rain_boots',
            'attribute'  => 'product_model_media',
            'scope'      => null,
            'locale'     => 'en_US',
            'type'       => 'product_model',
        ]);

        $this->assertSame(MediaSanitizer::MEDIA_ATTRIBUTE_DATA_COMPARISON, MediaSanitizer::sanitize($response));

        $mediaFiles = $api->listPerPage(10)->getItems();

        $this->assertCount($initialCount + 1, $mediaFiles);

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
