<?php

namespace Akeneo\Pim\tests\v1_8\Api\Currency;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class ListCurrencyApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getCurrencyApi();
        $expectedCurrencies = $this->getExpectedCurrencies();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(3);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=2&limit=3&with_count=false', $firstPage->getNextLink());

        $currencies = $firstPage->getItems();
        $this->assertCount(3 ,$currencies);
        for ($i = 0; $i < 3; $i++) {
            $this->assertSameContent($expectedCurrencies[$i], $currencies[$i]);
        }

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=1&limit=3&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=3&limit=3&with_count=false', $secondPage->getNextLink());

        $currencies = $secondPage->getItems();
        $this->assertCount(3 ,$currencies);
        for ($i = 0; $i < 3; $i++) {
            $this->assertSameContent($expectedCurrencies[$i + 3], $currencies[$i]);
        }

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=2&limit=3&with_count=false', $lastPage->getPreviousLink());

        $currencies = $lastPage->getItems();
        $this->assertCount(1 ,$currencies);
        $this->assertSameContent($expectedCurrencies[6], $currencies[0]);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSameContent($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getCurrencyApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(7, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(7, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=2&limit=7&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getCurrencyApi();
        $expectedCurrencies = $this->getExpectedCurrencies();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/currencies?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $currencies = $firstPage->getItems();
        $this->assertCount(2 ,$currencies);
        $this->assertSameContent($expectedCurrencies[0], $currencies[0]);
        $this->assertSameContent($expectedCurrencies[1], $currencies[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getCurrencyApi();
        $currencies = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $currencies);

        $attributes = iterator_to_array($currencies);

        $this->assertCount(7, $currencies);
        $this->assertSameContent($this->getExpectedCurrencies(), $attributes);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getCurrencyApi();
        $currencies = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $currencies);

        $currencies = iterator_to_array($currencies);

        $this->assertCount(7, $currencies);
        $this->assertSameContent($this->getExpectedCurrencies(), $currencies);
    }

    /**
     * @return array
     */
    public function getExpectedCurrencies()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/AUD',
                    ],
                ],
                'code'    => 'AUD',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/CHF',
                    ],
                ],
                'code'    => 'CHF',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/EUR',
                    ],
                ],
                'code'    => 'EUR',
                'enabled' => true,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/GBP',
                    ],
                ],
                'code'    => 'GBP',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/JPY',
                    ],
                ],
                'code'    => 'JPY',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/RUB',
                    ],
                ],
                'code'    => 'RUB',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/currencies/USD',
                    ],
                ],
                'code'    => 'USD',
                'enabled' => true,
            ],
        ];
    }
}
