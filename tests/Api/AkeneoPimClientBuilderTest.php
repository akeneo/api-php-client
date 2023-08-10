<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\ProductApi;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use ECSPrefix202306\Symfony\Component\VarDumper\VarDumper;
use PHPUnit\Framework\Assert;

final class AkeneoPimClientBuilderTest extends ApiTestCase
{
    public function test_error()
    {
        $this->server->setResponseOfPath(
            '/' . ProductApi::PRODUCTS_URI,
            new ResponseStack(
                new Response('', [], 429),
                new Response('', [], 429),
                new Response('', [], 429),
                new Response('', [], 429),
                new Response('', [], 201),
            )
        );

        $api = $this->createClientByPassword()->getProductApi();
        $response = $api->create('new_shoes', []);



        for ($i = 0; $i < 5; $i++) {
            $this->server->getRequestByOffset($i);
            VarDumper::dump($this->server->getRequestByOffset(0));

            Assert::assertSame(201, $response);
        }
    }

    protected function createClientByPassword(): AkeneoPimClientInterface
    {
        $clientBuilder = new AkeneoPimClientBuilder($this->server->getServerRoot(), [
            'retry' => true,
            'max-retry' => 5,
        ]);

        return $clientBuilder->buildAuthenticatedByPassword(
            'client_id',
            'secret',
            'username',
            'password'
        );
    }
}
