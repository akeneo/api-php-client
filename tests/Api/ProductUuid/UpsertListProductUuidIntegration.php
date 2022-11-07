<?php

namespace Akeneo\Pim\ApiClient\tests\Api\ProductUuid;

use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class UpsertListProductUuidIntegration extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->server->setResponseOfPath(
            '/' . ProductUuidApi::PRODUCTS_UUID_URI,
            new ResponseStack(
                new Response($this->getResults(), [], HttpClient::HTTP_OK)
            )
        );
    }

    public function test_upsert_list()
    {
        $api = $this->createClientByPassword()->getProductUuidApi();
        $response = $api->upsertList($this->getProductToUpsert());

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            $this->getProductToUpsertJson()
        );

        Assert::assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        Assert::assertCount(2, $responseLines);

        Assert::assertSame([
            'line' => 1,
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
            'status_code' => HttpClient::HTTP_NO_CONTENT,
        ], $responseLines[1]);

        Assert::assertSame([
            'line' => 2,
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f4',
            'status_code' => HttpClient::HTTP_CREATED,
        ], $responseLines[2]);
    }

    public function test_upsert_list_from_stream()
    {
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $this->getProductToUpsertJson());
        rewind($resources);

        $streamedResources = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($resources);
        $api = $this->createClientByPassword()->getProductUuidApi();
        $response = $api->upsertList($streamedResources);

        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT],
            $this->getProductToUpsertJson()
        );

        Assert::assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        Assert::assertCount(2, $responseLines);

        Assert::assertSame([
            'line' => 1,
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
            'status_code' => HttpClient::HTTP_NO_CONTENT,
        ], $responseLines[1]);

        Assert::assertSame([
            'line' => 2,
            'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f4',
            'status_code' => HttpClient::HTTP_CREATED,
        ], $responseLines[2]);
    }

    private function getProductToUpsertJson(): string
    {
        return <<<JSON
{"uuid":"12951d98-210e-4bRC-ab18-7fdgf1bd14f3","identifier":"docks_black","enabled":false,"values":{"name":[{"locale":"en_US","scope":null,"data":"Black Docks"}]}}
{"uuid":"12951d98-210e-4bRC-ab18-7fdgf1bd14f4","identifier":"pumps","enabled":false,"family":"sandals","categories":["summer_collection"],"values":{"name":[{"data":"The pumps","locale":"en_US","scope":null},{"data":"Les pumps","locale":"fr_FR","scope":null}]}}
JSON;
    }

    private function getProductToUpsert(): array
    {
        return [
            [
                'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
                'identifier' => 'docks_black',
                'enabled' => false,
                'values' => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope' => null,
                            'data' => 'Black Docks',
                        ],
                    ],
                ],
            ],
            [
                'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f4',
                'identifier' => 'pumps',
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
            ],
        ];
    }

    private function getResults(): string
    {
        return <<<JSON
        {"line": 1,"uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f3","status_code": 204}
        {"line": 2,"uuid": "12951d98-210e-4bRC-ab18-7fdgf1bd14f4","status_code": 201}
JSON;
    }
}
