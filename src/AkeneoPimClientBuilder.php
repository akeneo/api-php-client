<?php

namespace Akeneo\Pim\ApiClient;

use Akeneo\Pim\ApiClient\Api\AssociationTypeApi;
use Akeneo\Pim\ApiClient\Api\AttributeApi;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApi;
use Akeneo\Pim\ApiClient\Api\AttributeOptionApi;
use Akeneo\Pim\ApiClient\Api\AuthenticationApi;
use Akeneo\Pim\ApiClient\Api\CategoryApi;
use Akeneo\Pim\ApiClient\Api\ChannelApi;
use Akeneo\Pim\ApiClient\Api\CurrencyApi;
use Akeneo\Pim\ApiClient\Api\FamilyApi;
use Akeneo\Pim\ApiClient\Api\FamilyVariantApi;
use Akeneo\Pim\ApiClient\Api\LocaleApi;
use Akeneo\Pim\ApiClient\Api\MeasureFamilyApi;
use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use Akeneo\Pim\ApiClient\Api\ProductModelApi;
use Akeneo\Pim\ApiClient\Client\AuthenticatedHttpClient;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Client\ResourceClient;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Akeneo\Pim\ApiClient\FileSystem\LocalFileSystem;
use Akeneo\Pim\ApiClient\Pagination\PageFactory;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactory;
use Akeneo\Pim\ApiClient\Routing\UriGenerator;
use Akeneo\Pim\ApiClient\Security\Authentication;
use Akeneo\Pim\ApiClient\Stream\MultipartStreamBuilderFactory;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponseFactory;
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

    /** @var FileSystemInterface */
    protected $fileSystem;

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
     * Allows to define another implementation than LocalFileSystem
     *
     * @param FileSystemInterface $fileSystem
     *
     * @return AkeneoPimClientBuilder
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;

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
        list($resourceClient, $pageFactory, $cursorFactory, $fileSystem) = $this->setUp($authentication);

        $client = new AkeneoPimClient(
            $authentication,
            new ProductApi($resourceClient, $pageFactory, $cursorFactory),
            new CategoryApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeOptionApi($resourceClient, $pageFactory, $cursorFactory),
            new AttributeGroupApi($resourceClient, $pageFactory, $cursorFactory),
            new FamilyApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductMediaFileApi($resourceClient, $pageFactory, $cursorFactory, $fileSystem),
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
        $fileSystem = null !== $this->fileSystem ? $this->fileSystem : new LocalFileSystem();

        return [$resourceClient, $pageFactory, $cursorFactory, $fileSystem];
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
