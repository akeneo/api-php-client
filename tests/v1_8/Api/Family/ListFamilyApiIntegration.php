<?php

namespace Akeneo\Pim\tests\v1_8\Api\Family;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class ListFamilyApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getFamilyApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];
        $expectedFamilies = $this->getExpectedFamilies();

        $firstPage = $api->listPerPage(2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/families?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $families = $firstPage->getItems();
        $this->assertCount(2 ,$families);
        $this->assertSameContent($expectedFamilies[0], $families[0]);
        $this->assertSameContent($expectedFamilies[1], $families[1]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/families?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/families?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $families = $secondPage->getItems();
        $this->assertCount(2 ,$families);
        $this->assertSameContent($expectedFamilies[2], $families[0]);
        $this->assertSameContent($expectedFamilies[3], $families[1]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/families?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());

        $families = $lastPage->getItems();
        $this->assertCount(0 ,$families);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getFamilyApi();
        $expectedFamilies = $this->getExpectedFamilies();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/families?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $families = $firstPage->getItems();
        $this->assertCount(2 ,$families);
        $this->assertSameContent($expectedFamilies[0], $families[0]);
        $this->assertSameContent($expectedFamilies[1], $families[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getFamilyApi();
        $families = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $families);

        $families = iterator_to_array($families);

        $this->assertCount(4, $families);
        $this->assertSameContent($this->getExpectedFamilies(), $families);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getFamilyApi();
        $families = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $families);

        $families = iterator_to_array($families);

        $this->assertCount(4, $families);
        $this->assertSameContent($this->getExpectedFamilies(), $families);
    }

    /**
     * @return array
     */
    protected function getExpectedFamilies()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/boots',
                    ],
                ],
                'code' => 'boots',
                'attributes' => [
                    'color',
                    'description',
                    'manufacturer',
                    'name',
                    'price',
                    'side_view',
                    'size',
                    'sku',
                    'weather_conditions',
                ],
                'attribute_as_label' => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'description',
                        'name',
                        'price',
                        'side_view',
                        'size',
                        'sku',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Boots',
                    'fr_FR' => 'Bottes',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/heels',
                    ],
                ],
                'code' => 'heels',
                'attributes' => [
                    'manufacturer',
                    'name',
                    'price',
                    'sku',
                ],
                'attribute_as_label' => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'name',
                        'price',
                        'sku',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Heels',
                    'fr_FR' => 'Talons',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/sandals',
                    ],
                ],
                'code' => 'sandals',
                'attributes' => [
                    'color',
                    'description',
                    'destocking_date',
                    'manufacturer',
                    'name',
                    'price',
                    'side_view',
                    'size',
                    'sku',
                ],
                'attribute_as_label' => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'description',
                        'name',
                        'price',
                        'side_view',
                        'size',
                        'sku',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Sandals',
                    'fr_FR' => 'Sandales',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/families/sneakers',
                    ],
                ],
                'code' => 'sneakers',
                'attributes' => [
                    'color',
                    'description',
                    'length',
                    'manufacturer',
                    'name',
                    'price',
                    'side_view',
                    'size',
                    'sku',
                    'weather_conditions',
                ],
                'attribute_as_label' => 'name',
                'attribute_requirements' => [
                    'ecommerce' => [
                        'color',
                        'name',
                        'price',
                        'size',
                        'sku',
                    ],
                    'mobile' => [
                        'name',
                        'sku',
                    ],
                ],
                'labels' => [
                    'en_US' => 'Sneakers',
                    'fr_FR' => 'Sneakers',
                ],
            ],
        ];
    }
}
