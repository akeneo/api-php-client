<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Channel;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class ListChannelApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getChannelAPi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];
        $expectedChannels = $this->getExpectedChannels();

        $firstPage = $api->listPerPage(1);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=2&limit=1&with_count=false', $firstPage->getNextLink());

        $channels = $firstPage->getItems();
        $this->assertCount(1 ,$channels);
        $this->assertSameContent($expectedChannels[0], $channels[0]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=1&limit=1&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=3&limit=1&with_count=false', $secondPage->getNextLink());

        $channels = $secondPage->getItems();
        $this->assertCount(1 ,$channels);
        $this->assertSameContent($expectedChannels[1], $channels[0]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=2&limit=1&with_count=false', $lastPage->getPreviousLink());
        $this->assertCount(0, $lastPage->getItems());

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getChannelApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(1, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(2, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=2&limit=1&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getChannelApi();
        $expectedChannels = $this->getExpectedChannels();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(1, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/channels?page=2&limit=1&with_count=false&foo=bar', $firstPage->getNextLink());

        $channels = $firstPage->getItems();
        $this->assertCount(1 ,$channels);
        $this->assertSameContent($expectedChannels[0], $channels[0]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getChannelApi();
        $channels = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $channels);

        $channels = iterator_to_array($channels);

        $this->assertCount(2, $channels);
        $this->assertSameContent($this->getExpectedChannels(), $channels);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getChannelApi();
        $channels = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $channels);

        $channels = iterator_to_array($channels);

        $this->assertCount(2, $channels);
        $this->assertSameContent($this->getExpectedChannels(), $channels);
    }

    /**
     * @return array
     */
    public function getExpectedChannels()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/channels/ecommerce',
                    ],
                ],
                'code'             => 'ecommerce',
                'currencies'       => [
                    'USD',
                    'EUR',
                ],
                'locales'          => [
                    'en_US',
                    'fr_FR',
                ],
                'category_tree'    => '2014_collection',
                'conversion_units' => [
                ],
                'labels'           => [
                    'en_US' => 'Ecommerce',
                    'de_DE' => 'Ecommerce',
                    'fr_FR' => 'Ecommerce',
                ],
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/channels/mobile',
                    ],
                ],
                'code'             => 'mobile',
                'currencies'       => [
                    'EUR',
                ],
                'locales'          => [
                    'en_US',
                ],
                'category_tree'    => '2014_collection',
                'conversion_units' => [
                ],
                'labels'           => [
                    'en_US' => 'Mobile',
                    'de_DE' => 'Mobil',
                    'fr_FR' => 'Mobile',
                ],
            ],
        ];
    }
}
