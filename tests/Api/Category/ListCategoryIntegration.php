<?php

namespace Akeneo\Pim\tests\Api\Category;

use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Api\ApiTestCase;

class ListCategoryIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getCategoryApi();
        $expectedCategories = $this->getExpectedCategories();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api
            ->listPerPage()
            ->limit(2)
            ->get();

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $categories = $firstPage->getItems();
        $this->assertCount(2 ,$categories);
        $this->assertSameContent($expectedCategories[0], $categories[0]);
        $this->assertSameContent($expectedCategories[1], $categories[1]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $categories = $secondPage->getItems();
        $this->assertCount(2 ,$categories);
        $this->assertSameContent($expectedCategories[2], $categories[0]);
        $this->assertSameContent($expectedCategories[3], $categories[1]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());
        $this->assertNull($lastPage->getNextLink());
        $this->assertNull($lastPage->getNextPage());

        $categories = $lastPage->getItems();
        $this->assertCount(1 ,$categories);
        $this->assertSameContent($expectedCategories[4], $categories[0]);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getCategoryApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api
            ->listPerPage()
            ->withCount()
            ->limit(2)
            ->get();

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(5, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=2&limit=2&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getCategoryApi();
        $expectedCategories = $this->getExpectedCategories();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api
            ->listPerPage()
            ->limit(2)
            ->withoutCount()
            ->addQueryParameter('foo', 'bar')
            ->get();

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/categories?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $categories = $firstPage->getItems();
        $this->assertCount(2 ,$categories);
        $this->assertSameContent($expectedCategories[0], $categories[0]);
        $this->assertSameContent($expectedCategories[1], $categories[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getCategoryApi();
        $categories = $api->all()->get();

        $this->assertInstanceOf(ResourceCursorInterface::class, $categories);

        $categories = iterator_to_array($categories);

        $this->assertCount(5, $categories);
        $this->assertSameContent($this->getExpectedCategories(), $categories);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getCategoryApi();
        $categories = $api->all()->pageSize(10)->addQueryParameter('foo', 'bar')->get();

        $this->assertInstanceOf(ResourceCursorInterface::class, $categories);

        $categories = iterator_to_array($categories);

        $this->assertCount(5, $categories);
        $this->assertSameContent($this->getExpectedCategories(), $categories);
    }

    /**
     * @return array
     */
    protected function getExpectedCategories()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/categories/2014_collection',
                    ],
                ],
                'code'   => '2014_collection',
                'parent' => null,
                'labels' => [
                    'en_US' => '2014 collection',
                    'fr_FR' => 'collection 2014',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/categories/summer_collection',
                    ],
                ],
                'code'   => 'summer_collection',
                'parent' => '2014_collection',
                'labels' => [
                    'en_US' => 'Summer collection',
                    'fr_FR' => 'Collection été',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/categories/sandals',
                    ],
                ],
                'code'   => 'sandals',
                'parent' => 'summer_collection',
                'labels' => [
                    'en_US' => 'Sandals',
                    'fr_FR' => 'Sandales',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/categories/winter_collection',
                    ],
                ],
                'code'   => 'winter_collection',
                'parent' => '2014_collection',
                'labels' => [
                    'en_US' => 'Winter collection',
                    'fr_FR' => 'Collection hiver',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/categories/winter_boots',
                    ],
                ],
                'code'   => 'winter_boots',
                'parent' => 'winter_collection',
                'labels' => [
                    'en_US' => 'Winter boots',
                    'fr_FR' => 'Bottes d\'hiver',
                ],
            ]
        ];
    }
}
