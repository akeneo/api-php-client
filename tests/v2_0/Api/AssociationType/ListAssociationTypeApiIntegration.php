<?php

namespace Akeneo\Pim\ApiClient\tests\v2_0\Api\AssociationType;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class ListAssociationTypeApiIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];
        $expectedAssociationTypes = $this->getExpectedAssociationTypes();

        $firstPage = $api->listPerPage(2);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=2&limit=2&with_count=false', $firstPage->getNextLink());

        $associationTypes = $firstPage->getItems();
        $this->assertCount(2 ,$associationTypes);
        $this->assertSameContent($expectedAssociationTypes[0], $associationTypes[0]);
        $this->assertSameContent($expectedAssociationTypes[1], $associationTypes[1]);

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=1&limit=2&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=3&limit=2&with_count=false', $secondPage->getNextLink());

        $associationTypes = $secondPage->getItems();
        $this->assertCount(2 ,$associationTypes);
        $this->assertSameContent($expectedAssociationTypes[2], $associationTypes[0]);
        $this->assertSameContent($expectedAssociationTypes[3], $associationTypes[1]);

        $lastPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=2&limit=2&with_count=false', $lastPage->getPreviousLink());
        $this->assertCount(0, $lastPage->getItems());

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($secondPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(1, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(4, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=2&limit=1&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $expectedAssociationTypes = $this->getExpectedAssociationTypes();
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        $firstPage = $api->listPerPage(1, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/association-types?page=2&limit=1&with_count=false&foo=bar', $firstPage->getNextLink());

        $associationTypes = $firstPage->getItems();
        $this->assertCount(1 ,$associationTypes);
        $this->assertSameContent($expectedAssociationTypes[0], $associationTypes[0]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $associationTypes = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $associationTypes);

        $associationTypes = iterator_to_array($associationTypes);

        $this->assertCount(4, $associationTypes);
        $this->assertSameContent($this->getExpectedAssociationTypes(), $associationTypes);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getAssociationTypeApi();
        $associationTypes = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $associationTypes);

        $associationTypes = iterator_to_array($associationTypes);

        $this->assertCount(4, $associationTypes);
        $this->assertSameContent($this->getExpectedAssociationTypes(), $associationTypes);
    }

    /**
     * @return array
     */
    public function getExpectedAssociationTypes()
    {
        $baseUri = $this->getConfiguration()['pim']['base_uri'];

        return [
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/association-types/PACK',
                    ],
                ],
                'code'   => 'PACK',
                'labels' => [
                    'en_US' => 'Pack',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/association-types/SUBSTITUTION',
                    ],
                ],
                'code'   => 'SUBSTITUTION',
                'labels' => [
                    'en_US' => 'Substitution',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/association-types/UPSELL',
                    ],
                ],
                'code'   => 'UPSELL',
                'labels' => [
                    'en_US' => 'Upsell',
                ],
            ],
            [
                '_links' => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/association-types/X_SELL',
                    ],
                ],
                'code'   => 'X_SELL',
                'labels' => [
                    'en_US' => 'Cross sell',
                ],
            ],
        ];
    }
}
