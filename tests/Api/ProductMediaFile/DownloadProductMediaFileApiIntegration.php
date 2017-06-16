<?php

namespace Akeneo\Pim\tests\Api\ProductMediaFile;

use Akeneo\Pim\tests\Api\ApiTestCase;
use Psr\Http\Message\StreamInterface;

class DownloadProductMediaFileApiIntegration extends ApiTestCase
{
    public function testDownload()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $expectedMediaFile = realpath(__DIR__ . '/../../fixtures/akeneo.png');

        $api->create($expectedMediaFile, [
            'identifier' => 'medium_boot',
            'attribute'  => 'side_view',
            'scope'      => null,
            'locale'     => null,
        ]);

        // TODO: use code returned by creation when API-263 done
        $mediaFiles = $api->listPerPage()->getItems();
        $mediaFile = $api->download($mediaFiles[4]['code']);

        $this->assertInstanceOf(StreamInterface::class, $mediaFile);
        $this->assertSame(file_get_contents($expectedMediaFile), $mediaFile->getContents());
    }
}
