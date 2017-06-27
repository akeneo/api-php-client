<?php

namespace Akeneo\Pim\tests\Api\Locale;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Api\ApiTestCase;

class ListLocaleApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getLocaleApi();
        $expectedLocales = $this->getExpectedLocales();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(3);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/locales?page=2&limit=3&with_count=false', $firstPage->getNextLink());

        $locales = $firstPage->getItems();
        $this->assertCount(3 ,$locales);
        $this->assertSameContent($expectedLocales[0], $locales[0]);
        $this->assertSameContent($expectedLocales[1], $locales[1]);
        $this->assertSameContent($expectedLocales[2], $locales[2]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/locales?page=1&limit=3&with_count=false', $secondPage->getPreviousLink());

        $locales = $secondPage->getItems();
        $this->assertCount(3 ,$locales);
        $this->assertSameContent($expectedLocales[3], $locales[0]);
        $this->assertSameContent($expectedLocales[4], $locales[1]);
        $this->assertSameContent($expectedLocales[5], $locales[2]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertCount(0 ,$lastPage->getItems());

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);

        $locales = $previousPage->getItems();
        $this->assertCount(3 ,$locales);
        $this->assertSameContent($expectedLocales[3], $locales[0]);
        $this->assertSameContent($expectedLocales[4], $locales[1]);
        $this->assertSameContent($expectedLocales[5], $locales[2]);
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getLocaleApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(3, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(6, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/locales?page=2&limit=3&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getLocaleApi();
        $expectedLocales = $this->getExpectedLocales();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/locales?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $locales = $firstPage->getItems();
        $this->assertCount(2 ,$locales);
        $this->assertSameContent($expectedLocales[0], $locales[0]);
        $this->assertSameContent($expectedLocales[1], $locales[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getLocaleApi();
        $locales = $api->all();
        $expectedLocales = $this->getExpectedLocales();

        $this->assertInstanceOf(ResourceCursorInterface::class, $locales);

        $localesCount = 0;
        foreach ($locales as $key => $locale) {
            $this->assertSameContent($expectedLocales[$key], $locale);
            $localesCount++;
        }

        $this->assertSame(6, $localesCount);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getLocaleApi();
        $locales = $api->all(10, ['foo' => 'bar']);
        $expectedLocales = $this->getExpectedLocales();

        $this->assertInstanceOf(ResourceCursorInterface::class, $locales);

        $localesCount = 0;
        foreach ($locales as $key => $locale) {
            $this->assertSameContent($expectedLocales[$key], $locale);
            $localesCount++;
        }

        $this->assertSame(6, $localesCount);
    }

    public function testSearchEnabledLocales()
    {
        $api = $this->createClient()->getLocaleApi();
        $locales = $api->listPerPage(10, true, [
            'search' => [
                'enabled' => [
                    [
                        'operator' => '=',
                        'value'    => true,
                    ]
                ]
            ]
        ]);

        $this->assertSame(2, $locales->getCount());

        $locales = $locales->getItems();

        $this->assertSameContent([
            'code'    => 'en_US',
            'enabled' => true,
        ], $locales[0]);

        $this->assertSameContent([
            'code'    => 'fr_FR',
            'enabled' => true,
        ], $locales[1]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testInvalidSearch()
    {
        $api = $this->createClient()->getLocaleApi();
        $api->listPerPage(10, true, [
            'search' => [
                'family' => [
                    [
                        'operator' => 'IN',
                        'value'    => ['boots'],
                    ]
                ]
            ]
        ]);
    }

    public function getExpectedLocales()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/de_DE',
                    ],
                ],
                'code'    => 'de_DE',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/en_GB',
                    ],
                ],
                'code'    => 'en_GB',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/en_US',
                    ],
                ],
                'code'    => 'en_US',
                'enabled' => true,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/fr_BE',
                    ],
                ],
                'code'    => 'fr_BE',
                'enabled' => false,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/fr_FR',
                    ],
                ],
                'code'    => 'fr_FR',
                'enabled' => true,
            ],
            [
                '_links'  => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/locales/it_IT',
                    ],
                ],
                'code'    => 'it_IT',
                'enabled' => false,
            ],
        ];
    }
}
