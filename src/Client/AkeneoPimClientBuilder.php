<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApi;
use Akeneo\Pim\Api\AttributeOptionApi;
use Akeneo\Pim\Api\AuthenticationApi;
use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Api\ChannelApi;
use Akeneo\Pim\Api\FamilyApi;
use Akeneo\Pim\Api\LocaleApi;
use Akeneo\Pim\Api\ProductApi;
use Akeneo\Pim\Api\ProductMediaFileApi;
use Akeneo\Pim\HttpClient\AuthenticatedHttpClient;
use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\MultipartStream\MultipartStreamBuilderFactory;
use Akeneo\Pim\Pagination\PageFactory;
use Akeneo\Pim\Pagination\ResourceCursorFactory;
use Akeneo\Pim\Routing\UriGenerator;
use Akeneo\Pim\Security\Authentication;
use Akeneo\Pim\Stream\UpsertResourceListResponseFactory;
use Http\Client\HttpClient as Client;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

/**
 * Builder of the class AkeneoPimClient.
 * This builder is in charge to instantiate and inject the dependencies.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClientBuilder
{
    /** @var string */
    protected $baseUri;

    /** @var Client */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var StreamFactory */
    protected $streamFactory;

    /**
     * @param string $baseUri  Base uri to request the API
     * @param array  $options  Option to customize Akeneo PIM Client
     */
    public function __construct($baseUri, $options = [])
    {
        $this->baseUri = $baseUri;
        $this->httpClient = isset($options['http_client']) ? $options['http_client'] : HttpClientDiscovery::find();
        $this->requestFactory = isset($options['request_factory']) ? $options['request_factory'] : MessageFactoryDiscovery::find();
        $this->streamFactory = isset($options['stream_factory']) ? $options['stream_factory'] : StreamFactoryDiscovery::find();
    }

    /**
     * Build the Akeneo PIM client authenticated by user name and password.
     *
     * @param string $clientId Client id to use for the authentication
     * @param string $secret   Secret associated to the client
     * @param string $username Username to use for the authentication
     * @param string $password Password associated to the username
     *
     * @return AkeneoPimClientInterface
     */
    public function buildAuthenticatedByPassword($clientId, $secret, $username, $password)
    {
        $authentication = Authentication::fromPassword($clientId, $secret, $username, $password);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * Build the Akeneo PIM client authenticated by token.
     *
     * @param string $clientId     Client id to use for the authentication
     * @param string $secret       Secret associated to the client
     * @param string $token        Token to use for the authentication
     * @param string $refreshToken Token to use to refresh the access token
     *
     * @return AkeneoPimClientInterface
     */
    public function buildAuthenticatedByToken($clientId, $secret, $token, $refreshToken)
    {
        $authentication = Authentication::fromToken($clientId, $secret, $token, $refreshToken);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * @param Authentication $authentication
     *
     * @return AkeneoPimClientInterface
     */
    protected function buildAuthenticatedClient(Authentication $authentication)
    {
        $uriGenerator = new UriGenerator($this->baseUri);

        $httpClient = new HttpClient($this->httpClient, $this->requestFactory);
        $authenticationApi = new AuthenticationApi($httpClient, $uriGenerator);
        $authenticatedHttpClient = new AuthenticatedHttpClient($httpClient, $authenticationApi, $authentication);

        $multipartStreamBuilderFactory = new MultipartStreamBuilderFactory($this->streamFactory);
        $upsertListResponseFactory = new UpsertResourceListResponseFactory();
        $resourceClient = new ResourceClient(
            $authenticatedHttpClient,
            $uriGenerator,
            $multipartStreamBuilderFactory,
            $this->streamFactory,
            $upsertListResponseFactory
        );

        $pageFactory = new PageFactory($authenticatedHttpClient);
        $cursorFactory = new ResourceCursorFactory();

        $client = new AkeneoPimClient(
            $authentication,
            new ProductApi($resourceClient, $pageFactory, $cursorFactory),
            new CategoryApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeOptionApi($resourceClient, $pageFactory, $cursorFactory),
            new FamilyApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductMediaFileApi($resourceClient, $pageFactory, $cursorFactory),
            new LocaleApi($resourceClient, $pageFactory, $cursorFactory),
            new ChannelApi($resourceClient, $pageFactory, $cursorFactory)
        );

        return $client;
    }
}
