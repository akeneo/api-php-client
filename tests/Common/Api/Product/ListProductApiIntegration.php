<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Product;

use Akeneo\Pim\ApiClient\Pagination\PageInterface;

class ListProductApiIntegration extends AbstractProductApiTestCase
{
    public function testSearchHavingNoResults()
    {
        $api = $this->createClient()->getProductApi();
        $products = $api->listPerPage(10, true, [
            'search'  => [
                'name' => [
                    [
                        'operator' => '=',
                        'value'    => 'No name',
                        'locale'   => 'en_US',
                    ]
                ]
            ]
        ]);

        $this->assertInstanceOf(PageInterface::class, $products);
        $this->assertSame(0, $products->getCount());
        $this->assertEmpty($products->getItems());
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testSearchFailedWithInvalidOperator()
    {
        $api = $this->createClient()->getProductApi();
        $api->listPerPage(10, true, [
            'search'  => [
                'family' => [
                    [
                        'operator' => '=',
                        'value'    => 'Invalid operator for Family',
                    ]
                ]
            ]
        ]);
    }
}
