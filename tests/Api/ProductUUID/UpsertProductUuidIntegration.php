<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class UpsertProductUuidIntegration extends ApiTestCase
{
    public function test_upsert_product()
    {
        $this->server->setResponseOfPath(
            '/'. sprintf(ProductUuidApi::PRODUCT_UUID_URI, '12951d98-210e-4bRC-ab18-7fdgf1bd14f3'),
            new ResponseStack(
                new Response('', [], HttpClient::HTTP_NO_CONTENT)
            )
        );

        $api = $this->createClientByPassword()->getProductUuidApi();

        $parameters = [
            'identifier' => 'black_docks',
            'enabled' => false,
            'values'  => [
                'name' => [
                    [
                        'locale' => 'en_US',
                        'scope'  => null,
                        'data'   => 'Black Docks',
                    ],
                ],
            ]
        ];
        $response = $api->upsert('12951d98-210e-4bRC-ab18-7fdgf1bd14f3', $parameters);

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_INPUT], json_encode($parameters));

        Assert::assertSame(HttpClient::HTTP_NO_CONTENT, $response);
    }

}
