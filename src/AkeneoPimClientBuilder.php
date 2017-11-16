<?php

namespace Akeneo\Pim;

use Akeneo\Pim\Api\AssociationTypeApi;
use Akeneo\Pim\Api\AttributeApi;
use Akeneo\Pim\Api\AttributeGroupApi;
use Akeneo\Pim\Api\AttributeOptionApi;
use Akeneo\Pim\Api\AuthenticationApi;
use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Api\ChannelApi;
use Akeneo\Pim\Api\CurrencyApi;
use Akeneo\Pim\Api\FamilyApi;
use Akeneo\Pim\Api\FamilyVariantApi;
use Akeneo\Pim\Api\LocaleApi;
use Akeneo\Pim\Api\MeasureFamilyApi;
use Akeneo\Pim\Api\ProductApi;
use Akeneo\Pim\Api\ProductMediaFileApi;
use Akeneo\Pim\Api\ProductModelApi;
use Akeneo\Pim\Client\AuthenticatedHttpClient;
use Akeneo\Pim\Client\HttpClient;
use Akeneo\Pim\Client\ResourceClient;
use Akeneo\Pim\Pagination\PageFactory;
use Akeneo\Pim\Pagination\ResourceCursorFactory;
use Akeneo\Pim\Routing\UriGenerator;
use Akeneo\Pim\Security\Authentication;
use Akeneo\Pim\Stream\MultipartStreamBuilderFactory;
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
     * @param string $baseUri Base uri to request the API
     */
    public function __construct($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Allows to directly set a client instead of using HttpClientDiscovery::find()
     *
     * @param Client $httpClient
     *
     * @return AkeneoPimClientBuilder
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Allows to directly set a request factory instead of using MessageFactoryDiscovery::find()
     *
     * @param RequestFactory $requestFactory
     *
     * @return AkeneoPimClientBuilder
     */
    public function setRequestFactory($requestFactory)
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * Allows to directly set a stream factory instead of using StreamFactoryDiscovery::find()
     *
     * @param StreamFactory $streamFactory
     *
     * @return AkeneoPimClientBuilder
     */
    public function setStreamFactory($streamFactory)
    {
        $this->streamFactory = $streamFactory;

        return $this;
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
        list($resourceClient, $pageFactory, $cursorFactory) = $this->setUp($authentication);

        $client = new AkeneoPimClient(
            $authentication,
            new ProductApi($resourceClient, $pageFactory, $cursorFactory),
            new CategoryApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeOptionApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeGroupApi($resourceClient, $pageFactory, $cursorFactory),
            new FamilyApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductMediaFileApi($resourceClient, $pageFactory, $cursorFactory),
            new LocaleApi($resourceClient, $pageFactory, $cursorFactory),
            new ChannelApi($resourceClient, $pageFactory, $cursorFactory),
            new CurrencyApi($resourceClient, $pageFactory, $cursorFactory),
            new MeasureFamilyApi($resourceClient, $pageFactory, $cursorFactory),
            new AssociationTypeApi($resourceClient, $pageFactory, $cursorFactory),
            new FamilyVariantApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductModelApi($resourceClient, $pageFactory, $cursorFactory)
        );

        return $client;
    }

    /**
     * @param Authentication $authentication
     *
     * @return array
     */
    protected function setUp(Authentication $authentication)
    {
        $uriGenerator = new UriGenerator($this->baseUri);

        $httpClient = new HttpClient($this->getHttpClient(), $this->getRequestFactory());
        $authenticationApi = new AuthenticationApi($httpClient, $uriGenerator);
        $authenticatedHttpClient = new AuthenticatedHttpClient($httpClient, $authenticationApi, $authentication);

        $multipartStreamBuilderFactory = new MultipartStreamBuilderFactory($this->getStreamFactory());
        $upsertListResponseFactory = new UpsertResourceListResponseFactory();
        $resourceClient = new ResourceClient(
            $authenticatedHttpClient,
            $uriGenerator,
            $multipartStreamBuilderFactory,
            $upsertListResponseFactory
        );

        $pageFactory = new PageFactory($authenticatedHttpClient);
        $cursorFactory = new ResourceCursorFactory();

        return [$resourceClient, $pageFactory, $cursorFactory];
    }

    /**
     * @return Client
     */
    private function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @return RequestFactory
     */
    private function getRequestFactory()
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = MessageFactoryDiscovery::find();
        }

        return $this->requestFactory;
    }

    /**
     * @return StreamFactory
     */
    private function getStreamFactory()
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = StreamFactoryDiscovery::find();
        }

        return $this->streamFactory;
    }
}
