<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class CreateProductTest extends ApiTestCase
{
    public function test_create_product()
    {
        $this->server->setResponseOfPath(
            '/'. ProductApi::PRODUCTS_URI,
            new ResponseStack(
                new Response('', [], 201)
            )
        );

        $api = $this->createClient()->getProductApi();
        $response = $api->create('new_shoes', $this->newProduct());

        Assert::assertSame(json_encode($this->expectedProduct()), $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT]);

        Assert::assertSame(201, $response);
    }

    public function test_create_invalid_product()
    {
        $this->server->setResponseOfPath(
            '/'. ProductApi::PRODUCTS_URI,
            new ResponseStack(
                new Response('{"code": 422, "message":"The value black_sneakers is already set on another product for the unique attribute sku"}', [], 422)
                )
            );
            
        $this->expectException(\Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('The value black_sneakers is already set on another product for the unique attribute sku');
        
        $api = $this->createClient()->getProductApi();
        $api->create('black_sneakers', $this->newProduct());
    }

    private function newProduct()
    {
        return [
            'enabled'    => false,
            'family'     => 'sandals',
            'categories' => ['summer_collection'],
            'values'     => [
                'name' => [
                    [
                        'data'   => 'The pumps',
                        'locale' => 'en_US',
                        'scope'  => null,
                    ],
                    [
                        'data'   => 'Les pumps',
                        'locale' => 'fr_FR',
                        'scope'  => null,
                    ]
                ]
            ]
        ];
    }

    private function expectedProduct()
    {
        return [
            'enabled'    => false,
            'family'     => 'sandals',
            'categories' => ['summer_collection'],
            'values'     => [
                'name' => [
                    [
                        'data'   => 'The pumps',
                        'locale' => 'en_US',
                        'scope'  => null,
                    ],
                    [
                        'data'   => 'Les pumps',
                        'locale' => 'fr_FR',
                        'scope'  => null,
                    ]
                ]
            ],
            'identifier' => 'new_shoes'
        ];
    }
}
