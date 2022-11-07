<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ProductUuid;

use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class CreateProductUuidIntegration extends ApiTestCase
{
    public function test_create_product()
    {
        $this->server->setResponseOfPath(
            '/' . ProductUuidApi::PRODUCTS_UUID_URI,
            new ResponseStack(
                new Response('', [], HttpClient::HTTP_CREATED)
            )
        );

        $api = $this->createClientByPassword()->getProductUuidApi();
        $response = $api->create('12951d98-210e-4bRC-ab18-7fdgf1bd14f3', $this->newProduct());

        Assert::assertSame(
            json_encode($this->expectedProduct()),
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT]
        );

        Assert::assertSame(HttpClient::HTTP_CREATED, $response);
    }

    public function test_create_invalid_product()
    {
        $this->server->setResponseOfPath(
            '/' . ProductUuidApi::PRODUCTS_UUID_URI,
            new ResponseStack(
                new Response(
                    '{"code": 422, "message":"The value 12951d98-210e-4bRC-ab18-7fdgf1bd14f3 is already set on another product for the uuid"}',
                    [],
                    HttpClient::HTTP_UNPROCESSABLE_ENTITY
                )
            )
        );

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage(
            'The value 12951d98-210e-4bRC-ab18-7fdgf1bd14f3 is already set on another product for the uuid'
        );

        $api = $this->createClientByPassword()->getProductUuidApi();
        $api->create('12951d98-210e-4bRC-ab18-7fdgf1bd14f3', $this->newProduct());
    }

    private function newProduct(): array
    {
        return [
            'identifier' => 'new_shoes',
            'enabled' => false,
            'family' => 'sandals',
            'categories' => ['summer_collection'],
            'values' => [
                'name' => [
                    [
                        'data' => 'The pumps',
                        'locale' => 'en_US',
                        'scope' => null,
                    ],
                    [
                        'data' => 'Les pumps',
                        'locale' => 'fr_FR',
                        'scope' => null,
                    ],
                ],
            ],
        ];
    }

    private function expectedProduct(): array
    {
        return [
            'identifier' => 'new_shoes',
            'enabled' => false,
            'family' => 'sandals',
            'categories' => ['summer_collection'],
            'values' => [
                'name' => [
                    [
                        'data' => 'The pumps',
                        'locale' => 'en_US',
                        'scope' => null,
                    ],
                    [
                        'data' => 'Les pumps',
                        'locale' => 'fr_FR',
                        'scope' => null,
                    ],
                ],
            ],
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
        ];
    }
}
