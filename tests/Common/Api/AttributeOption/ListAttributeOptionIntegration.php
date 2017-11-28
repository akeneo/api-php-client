<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\AttributeOption;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class ListAttributeOptionIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $expectedAttributeOptions = $this->getExpectedAttributeOptions();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage('weather_conditions', 2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $attributeOptions = $firstPage->getItems();
        $this->assertCount(2 ,$attributeOptions);
        $this->assertSameContent($expectedAttributeOptions[0], $attributeOptions[0]);
        $this->assertSameContent($expectedAttributeOptions[1], $attributeOptions[1]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $attributeOptions = $secondPage->getItems();
        $this->assertCount(2 ,$attributeOptions);
        $this->assertSameContent($expectedAttributeOptions[2], $attributeOptions[0]);
        $this->assertSameContent($expectedAttributeOptions[3], $attributeOptions[1]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());

        $attributeOptions = $lastPage->getItems();
        $this->assertCount(1 ,$attributeOptions);
        $this->assertSameContent($expectedAttributeOptions[4], $attributeOptions[0]);

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage('weather_conditions',2, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(5, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=2&limit=2&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $expectedAttributeOptions = $this->getExpectedAttributeOptions();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage('weather_conditions',2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/attributes/weather_conditions/options?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $attributeOptions = $firstPage->getItems();
        $this->assertCount(2 ,$attributeOptions);
        $this->assertSameContent($expectedAttributeOptions[0], $attributeOptions[0]);
        $this->assertSameContent($expectedAttributeOptions[1], $attributeOptions[1]);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testListPerPageOnAnUnknownAttribute()
    {
        $api = $this->createClient()->getAttributeOptionApi();

        $api->listPerPage('unknown_attribute');
    }

    public function testAll()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $attributeOptions = $api->all('weather_conditions');

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributeOptions);

        $attributeOptions = iterator_to_array($attributeOptions);

        $this->assertCount(5, $attributeOptions);
        $this->assertSameContent($this->getExpectedAttributeOptions(), $attributeOptions);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getAttributeOptionApi();
        $attributeOptions = $api->all('weather_conditions', 10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributeOptions);

        $attributeOptions = iterator_to_array($attributeOptions);

        $this->assertCount(5, $attributeOptions);
        $this->assertSameContent($this->getExpectedAttributeOptions(), $attributeOptions);
    }

    /**
     * @return array
     */
    protected function getExpectedAttributeOptions()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions/options/cold',
                    ],
                ],
                'code'       => 'cold',
                'attribute'  => 'weather_conditions',
                'sort_order' => 4,
                'labels'     => [
                    'en_US' => 'Cold',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions/options/dry',
                    ],
                ],
                'code'       => 'dry',
                'attribute'  => 'weather_conditions',
                'sort_order' => 1,
                'labels'     => [
                    'en_US' => 'Dry',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions/options/hot',
                    ],
                ],
                'code'       => 'hot',
                'attribute'  => 'weather_conditions',
                'sort_order' => 3,
                'labels'     => [
                    'en_US' => 'Hot',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions/options/snowy',
                    ],
                ],
                'code'       => 'snowy',
                'attribute'  => 'weather_conditions',
                'sort_order' => 5,
                'labels'     => [
                    'en_US' => 'Snowy',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attributes/weather_conditions/options/wet',
                    ],
                ],
                'code'       => 'wet',
                'attribute'  => 'weather_conditions',
                'sort_order' => 2,
                'labels'     => [
                    'en_US' => 'Wet',
                ],
            ],
        ];
    }
}
