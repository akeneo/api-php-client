<?php

namespace Akeneo\Pim\ApiClient\tests\Api;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AuthenticationApi;
use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var MockWebServer */
    protected $server;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
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
    protected function tearDown()
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

    /**
     * Extracts the code of a media-file from a creation response.
     *
     * @param $response
     *
     * @throws RuntimeException if unable to extract the code
     *
     * @return mixed
     */
    protected function extractCodeFromCreationResponse($response)
    {
        $headers = $response->getHeaders();

        if (!isset($headers['Location'][0])) {
            throw new RuntimeException('The response does not contain the URI of the created media-file.');
        }

        $matches = [];
        if (1 !== preg_match(ProductMediaFileApi::MEDIA_FILE_URI_CODE_REGEX, $headers['Location'][0], $matches)) {
            throw new RuntimeException('Unable to find the code in the URI of the created media-file.');
        }

        return $matches['code'];
    }

    /**
     * @param CursorInterface $result
     * @param array $expected
     */
    protected function assertSameResults()
    {
        $products = [];
        foreach ($result as $product) {
            $products[] = $product->getIdentifier();
        }

        $this->assertSame($products, $expected);
    }
}
