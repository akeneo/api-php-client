<?php

namespace tests\integration\Akeneo\Pim\Api;

use Akeneo\Pim\Api\AuthenticationApi;
use Akeneo\Pim\Client\ResourceClient;
use Akeneo\Pim\HttpClient\AuthenticatedHttpClient;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use Akeneo\Pim\Security\Authentication;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\ResponseFactory;
use Http\Mock\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function createHttpClientMock()
    {
        $responseFactoryStub = $this->createMock(ResponseFactory::class);
        $responseBodyContent =
<<<JSON
{
  "access_token": "foo",
  "expires_in": 3600,
  "token_type": "bearer",
  "scope": null,
  "refresh_token": "bar"
}
JSON;
        $authenticationResponse = $this->createResponseMock(200, $responseBodyContent);

        $client = new Client($responseFactoryStub);
        $client->addResponse($authenticationResponse);

        return $client;
    }

    protected function createResponseMock($statusCode, $bodyContent)
    {
        $responseBody = $this->createMock(StreamInterface::class);
        $responseBody->method('getContents')->willReturn($bodyContent);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getBody')->willReturn($responseBody);

        return $response;
    }

    public function getResourceClient(HttpClient $httpClient, UriGeneratorInterface $uriGenerator)
    {
        $requestStub = $this->createMock(RequestInterface::class);
        $requestStub
            ->expects($this->any())
            ->method('withHeader')
            ->willReturn($requestStub);

        $requestFactoryStub = $this->createMock(RequestFactory::class);
        $requestFactoryStub
            ->expects($this->any())
            ->method('createRequest')
            ->willReturn($requestStub);

        $authentication = new Authentication(
            'client_id',
            'secret',
            'user',
            'password'
        );

        $basicHttpClient = new \Akeneo\Pim\HttpClient\HttpClient($httpClient, $requestFactoryStub);
        $authenticationApi = new AuthenticationApi($basicHttpClient, $uriGenerator);
        $authenticatedHttpClient = new AuthenticatedHttpClient($basicHttpClient, $authenticationApi, $authentication);

        return new ResourceClient($authenticatedHttpClient);
    }
}
