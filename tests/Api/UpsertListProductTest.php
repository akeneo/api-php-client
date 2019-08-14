<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use PHPUnit\Framework\Assert;

class UpsertListProductTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->server->setResponseOfPath(
            '/'. ProductApi::PRODUCTS_URI,
            new ResponseStack(
                new Response($this->getResults(), [], 200)
            )
        );
    }

    public function test_upsert_list()
    {
        $api = $this->createClient()->getProductApi();
        $response = $api->upsertList($this->getProductToUpsert());

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], $this->getProductToUpsertJson());

        Assert::assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        Assert::assertCount(2, $responseLines);

        Assert::assertSame([
            'line'        => 1,
            'identifier'  => 'docks_black',
            'status_code' => 204,
        ], $responseLines[1]);

        Assert::assertSame([
            'line'        => 2,
            'identifier'  => 'pumps',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    public function test_upsert_list_from_stream()
    {
        $resources = fopen('php://memory', 'w+');
        fwrite($resources, $this->getProductToUpsertJson());
        rewind($resources);

        $streamedResources = Psr17FactoryDiscovery::findStreamFactory()->createStreamFromResource($resources);
        $api = $this->createClient()->getProductAPi();
        $response = $api->upsertList($streamedResources);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], $this->getProductToUpsertJson());

        Assert::assertInstanceOf('\Iterator', $response);

        $responseLines = iterator_to_array($response);
        Assert::assertCount(2, $responseLines);

        Assert::assertSame([
            'line'        => 1,
            'identifier'  => 'docks_black',
            'status_code' => 204,
        ], $responseLines[1]);

        Assert::assertSame([
            'line'        => 2,
            'identifier'  => 'pumps',
            'status_code' => 201,
        ], $responseLines[2]);
    }

    private function getProductToUpsertJson(){
        return <<<JSON
{"identifier":"docks_black","enabled":false,"values":{"name":[{"locale":"en_US","scope":null,"data":"Black Docks"}]}}
{"identifier":"pumps","enabled":false,"family":"sandals","categories":["summer_collection"],"values":{"name":[{"data":"The pumps","locale":"en_US","scope":null},{"data":"Les pumps","locale":"fr_FR","scope":null}]}}
JSON;
    }

    private function getProductToUpsert(){
        return [
            [
                'identifier' => 'docks_black',
                'enabled'    => false,
                'values'     => [
                    'name' => [
                        [
                            'locale' => 'en_US',
                            'scope'  => null,
                            'data'   => 'Black Docks',
                        ],
                    ],
                ]
            ],
            [
                'identifier' => 'pumps',
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
                    ],
                ]
            ]
        ];
    }

    private function getResults()
    {
        return <<<JSON
        {"line": 1,"identifier": "docks_black","status_code": 204}
        {"line": 2,"identifier": "pumps","status_code": 201}
JSON;
    }

}
