<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AuthenticationApi;
use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\TestCase;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class ApiTestCase extends TestCase
{
    /** @var MockWebServer */
    protected $server;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->server = new MockWebServer(8081, '127.0.0.1');
        $this->server->start();

        $this->server->setResponseOfPath(
            '/'. AuthenticationApi::TOKEN_URI,
            new ResponseStack(
                new Response($this->getAuthenticatedJson())
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->server->stop();
    }

    /**
     * @return AkeneoPimClientInterface
     */
    protected function createClient()
    {
        $clientBuilder = new AkeneoPimClientBuilder($this->server->getServerRoot());

        return $clientBuilder->buildAuthenticatedByPassword(
            'client_id',
            'secret',
            'username',
            'password'
        );
    }

    private function getAuthenticatedJson()
    {
        return <<<JSON
            {
                "refresh_token" : "this-is-a-refresh-token",
                "access_token" : "this-is-an-access-token"
            }
JSON;
    }
}
