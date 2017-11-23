<?php

namespace Akeneo\Pim\tests\Common\Api\ProductMediaFile;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;
use Akeneo\Pim\tests\MediaSanitizer;

class ListProductMediaFileApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $expectedMediaFiles = $this->getExpectedMediaFiles();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(2);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/media-files?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $mediaFiles = $firstPage->getItems();

        $this->assertCount(2 ,$mediaFiles);
        $this->assertSameContent($expectedMediaFiles[0], $this->sanitizeMediaFile($mediaFiles[0]));
        $this->assertSameContent($expectedMediaFiles[1], $this->sanitizeMediaFile($mediaFiles[1]));

        $secondPage = $firstPage->getNextPage();

        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/media-files?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/media-files?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $mediaFiles = $secondPage->getItems();

        $this->assertCount(2 ,$mediaFiles);
        $this->assertSameContent($expectedMediaFiles[2], $this->sanitizeMediaFile($mediaFiles[0]));
        $this->assertSameContent($expectedMediaFiles[3], $this->sanitizeMediaFile($mediaFiles[1]));

        $lastPage = $secondPage->getNextPage();

        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertNull($lastPage->getNextPage());
        $this->assertCount(0, $lastPage->getItems());

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(2, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(4, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/media-files?page=2&limit=2&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];
        $expectedMediaFiles = $this->getExpectedMediaFiles();

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/media-files?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $mediaFiles = $firstPage->getItems();

        $this->assertCount(2 ,$mediaFiles);
        $this->assertSameContent($expectedMediaFiles[0], $this->sanitizeMediaFile($mediaFiles[0]));
        $this->assertSameContent($expectedMediaFiles[1], $this->sanitizeMediaFile($mediaFiles[1]));
    }

    public function testAll()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFiles = $api->all();
        $expectedMediaFiles = $this->getExpectedMediaFiles();

        $this->assertInstanceOf(ResourceCursorInterface::class, $mediaFiles);

        $mediaFilesCount = 0;
        foreach ($mediaFiles as $key => $mediaFile) {
            $this->assertSameContent($expectedMediaFiles[$key], $this->sanitizeMediaFile($mediaFile));
            $mediaFilesCount++;
        }

        $this->assertSame(4, $mediaFilesCount);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getProductMediaFileApi();
        $mediaFiles = $api->all(10, ['foo' => 'bar']);
        $expectedMediaFiles = $this->getExpectedMediaFiles();

        $this->assertInstanceOf(ResourceCursorInterface::class, $mediaFiles);

        $mediaFilesCount = 0;
        foreach ($mediaFiles as $key => $mediaFile) {
            $this->assertSameContent($expectedMediaFiles[$key], $this->sanitizeMediaFile($mediaFile));
            $mediaFilesCount++;
        }

        $this->assertSame(4, $mediaFilesCount);
    }

    /**
     * @return array
     */
    protected function getExpectedMediaFiles()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            $this->sanitizeMediaFile([
                '_links'            => [
                    'self'     => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/b/6/c/bb6ce0ef18bfa15d9e9fb4e3b26ce7064ac80b63_Ziggy_certification.jpg',
                    ],
                    'download' => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/b/6/c/bb6ce0ef18bfa15d9e9fb4e3b26ce7064ac80b63_Ziggy_certification.jpg/download',
                    ],
                ],
                'code'              => 'b/b/6/c/bb6ce0ef18bfa15d9e9fb4e3b26ce7064ac80b63_Ziggy_certification.jpg',
                'original_filename' => 'Ziggy-certification.jpg',
                'mime_type'         => 'image/jpeg',
                'size'              => 10513,
                'extension'         => 'jpg',
            ]),
            $this->sanitizeMediaFile([
                '_links'            => [
                    'self'     => [
                        'href' => $baseUri . '/api/rest/v1/media-files/a/d/d/f/addf77b39f4e38241861554afc7d0ed8e436c99c_Ziggy.png',
                    ],
                    'download' => [
                        'href' => $baseUri . '/api/rest/v1/media-files/a/d/d/f/addf77b39f4e38241861554afc7d0ed8e436c99c_Ziggy.png/download',
                    ],
                ],
                'code'              => 'a/d/d/f/addf77b39f4e38241861554afc7d0ed8e436c99c_Ziggy.png',
                'original_filename' => 'Ziggy.png',
                'mime_type'         => 'image/png',
                'size'              => 118039,
                'extension'         => 'png',
            ]),
            $this->sanitizeMediaFile([
                '_links'            => [
                    'self'     => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/b/f/b/bbfbb253c296b0c56ee641e94b032697c06ba9e7_Akeneo_logo.png',
                    ],
                    'download' => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/b/f/b/bbfbb253c296b0c56ee641e94b032697c06ba9e7_Akeneo_logo.png/download',
                    ],
                ],
                'code'              => 'b/b/f/b/bbfbb253c296b0c56ee641e94b032697c06ba9e7_Akeneo_logo.png',
                'original_filename' => 'Akeneo-logo.png',
                'mime_type'         => 'image/png',
                'size'              => 18929,
                'extension'         => 'png',
            ]),
            $this->sanitizeMediaFile([
                '_links'            => [
                    'self'     => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/8/2/3/b823fe85baf0a5fdc62a7864d2df430e7f1dbf77_Ziggy_certification.jpg',
                    ],
                    'download' => [
                        'href' => $baseUri . '/api/rest/v1/media-files/b/8/2/3/b823fe85baf0a5fdc62a7864d2df430e7f1dbf77_Ziggy_certification.jpg/download',
                    ],
                ],
                'code'              => 'b/8/2/3/b823fe85baf0a5fdc62a7864d2df430e7f1dbf77_Ziggy_certification.jpg',
                'original_filename' => 'Ziggy-certification.jpg',
                'mime_type'         => 'image/jpeg',
                'size'              => 10513,
                'extension'         => 'jpg',
            ])
        ];
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
