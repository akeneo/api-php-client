<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\AttributeGroup;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class ListAttributeGroupIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $expectedAttributeGroups = $this->getExpectedAttributeGroups();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $attributeGroups = $firstPage->getItems();
        $this->assertCount(2 ,$attributeGroups);
        for ($i = 0; $i < 2; $i++) {
            $this->assertSameContent($expectedAttributeGroups[$i], $attributeGroups[$i]);
        }

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $attributeGroups = $secondPage->getItems();
        $this->assertCount(2 ,$attributeGroups);
        for ($i = 0; $i < 2; $i++) {
            $this->assertSameContent($expectedAttributeGroups[2 + $i], $attributeGroups[$i]);
        }

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertTrue($lastPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=4&limit=2&with_count=false', $lastPage->getNextLink());

        $attributeGroups = $lastPage->getItems();
        $this->assertCount(2 ,$attributeGroups);
        for ($i = 0; $i < 2; $i++) {
            $this->assertSameContent($expectedAttributeGroups[4 + $i], $attributeGroups[$i]);
        }

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSameContent($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(4, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(6, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=2&limit=4&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $expectedAttributeGroups = $this->getExpectedAttributeGroups();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(2, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/attribute-groups?page=2&limit=2&with_count=false&foo=bar', $firstPage->getNextLink());

        $attributeGroups = $firstPage->getItems();
        $this->assertCount(2 ,$attributeGroups);
        $this->assertSameContent($expectedAttributeGroups[0], $attributeGroups[0]);
        $this->assertSameContent($expectedAttributeGroups[1], $attributeGroups[1]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $attributeGroups = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributeGroups);

        $attributeGroups = iterator_to_array($attributeGroups);

        $this->assertCount(6, $attributeGroups);
        $this->assertSameContent($this->getExpectedAttributeGroups(), $attributeGroups);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getAttributeGroupApi();
        $attributeGroups = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $attributeGroups);

        $attributeGroups = iterator_to_array($attributeGroups);

        $this->assertCount(6, $attributeGroups);
        $this->assertSameContent($this->getExpectedAttributeGroups(), $attributeGroups);
    }

    /**
     * @return array
     */
    protected function getExpectedAttributeGroups()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/colors',
                    ],
                ],
                'code'       => 'colors',
                'attributes' => ['color', 'heel_color', 'sole_color'],
                'sort_order' => 4,
                'labels'     => [
                    'en_US' => 'Colors',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/info',
                    ],
                ],
                'code'       => 'info',
                'attributes' => ['sku', 'name', 'manufacturer', 'weather_conditions', 'description', 'length'],
                'sort_order' => 1,
                'labels'     => [
                    'en_US' => 'Product information',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/marketing',
                    ],
                ],
                'code'       => 'marketing',
                'attributes' => ['price'],
                'sort_order' => 2,
                'labels'     => [
                    'en_US' => 'Marketing',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/media',
                    ],
                ],
                'code'       => 'media',
                'attributes' => ['side_view'],
                'sort_order' => 5,
                'labels'     => [
                    'en_US' => 'Media',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/other',
                    ],
                ],
                'code'       => 'other',
                'attributes' => ['number_in_stock', 'destocking_date', 'handmade'],
                'sort_order' => 100,
                'labels'     => [
                    'en_US' => 'Other',
                ],
            ],
            [
                '_links'     => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/attribute-groups/sizes',
                    ],
                ],
                'code'       => 'sizes',
                'attributes' => ['size'],
                'sort_order' => 3,
                'labels'     => [
                    'en_US' => 'Sizes',
                ],
            ],
        ];
    }
}
