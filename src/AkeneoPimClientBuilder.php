<?php

namespace Akeneo\Pim\ApiClient;

use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApi;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApi;
use Akeneo\Pim\ApiClient\Api\AssetApi;
use Akeneo\Pim\ApiClient\Api\AssetCategoryApi;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApi as AssetManagerApi;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApi;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApi;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApi;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApi;
use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApi;
use Akeneo\Pim\ApiClient\Api\AssetTagApi;
use Akeneo\Pim\ApiClient\Api\AssetVariationFileApi;
use Akeneo\Pim\ApiClient\Api\AssociationTypeApi;
use Akeneo\Pim\ApiClient\Api\AttributeApi;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApi;
use Akeneo\Pim\ApiClient\Api\AttributeOptionApi;
use Akeneo\Pim\ApiClient\Api\AuthenticationApi;
use Akeneo\Pim\ApiClient\Api\CategoryApi;
use Akeneo\Pim\ApiClient\Api\CategoryMediaFileApi;
use Akeneo\Pim\ApiClient\Api\ChannelApi;
use Akeneo\Pim\ApiClient\Api\CurrencyApi;
use Akeneo\Pim\ApiClient\Api\FamilyApi;
use Akeneo\Pim\ApiClient\Api\FamilyVariantApi;
use Akeneo\Pim\ApiClient\Api\LocaleApi;
use Akeneo\Pim\ApiClient\Api\MeasureFamilyApi;
use Akeneo\Pim\ApiClient\Api\MeasurementFamilyApi;
use Akeneo\Pim\ApiClient\Api\ProductApi;
use Akeneo\Pim\ApiClient\Api\ProductDraftApi;
use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApi;
use Akeneo\Pim\ApiClient\Api\ProductMediaFileApi;
use Akeneo\Pim\ApiClient\Api\ProductModelApi;
use Akeneo\Pim\ApiClient\Api\ProductModelDraftApi;
use Akeneo\Pim\ApiClient\Api\ProductUuidApi;
use Akeneo\Pim\ApiClient\Api\PublishedProductApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeOptionApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApi;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApi;
use Akeneo\Pim\ApiClient\Cache\LRUCache;
use Akeneo\Pim\ApiClient\Client\AuthenticatedHttpClient;
use Akeneo\Pim\ApiClient\Client\CachedResourceClient;
use Akeneo\Pim\ApiClient\Client\HttpClient;
use Akeneo\Pim\ApiClient\Client\Options;
use Akeneo\Pim\ApiClient\Client\ResourceClient;
use Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface;
use Akeneo\Pim\ApiClient\FileSystem\LocalFileSystem;
use Akeneo\Pim\ApiClient\Pagination\PageFactory;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactory;
use Akeneo\Pim\ApiClient\Routing\UriGenerator;
use Akeneo\Pim\ApiClient\Security\Authentication;
use Akeneo\Pim\ApiClient\Stream\MultipartStreamBuilderFactory;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponseFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

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
    /** @var ClientInterface */
    protected $httpClient;

    /** @var RequestFactoryInterface */
    protected $requestFactory;

    /** @var StreamFactoryInterface */
    protected $streamFactory;

    /** @var FileSystemInterface */
    protected $fileSystem;

    protected Options $options;

    protected bool $cacheEnabled = false;

    /**
     * @param string $baseUri Base uri to request the API
     */
    public function __construct(
        protected string $baseUri,
        array $options = []
    ) {
        $this->options = Options::fromArray($options);
    }

    /**
     * Allows to directly set a client instead of using the discovery
     */
    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Allows to directly set a request factory instead of using the discovery
     */
    public function setRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->requestFactory = $requestFactory;

        return $this;
    }

    /**
     * Allows to directly set a stream factory instead of using the discovery
     */
    public function setStreamFactory(StreamFactoryInterface $streamFactory): self
    {
        $this->streamFactory = $streamFactory;

        return $this;
    }

    /**
     * Allows to define another implementation than LocalFileSystem
     */
    public function setFileSystem(FileSystemInterface $fileSystem): self
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
     */
    public function buildAuthenticatedByPassword(
        string $clientId,
        string $secret,
        string $username,
        string $password
    ): AkeneoPimClientInterface {
        $authentication = Authentication::fromPassword($clientId, $secret, $username, $password);

        return $this->buildAuthenticatedClient($authentication);
    }

    public function buildAuthenticatedByAppToken(string $token): AkeneoPimClientInterface
    {
        $authentication = Authentication::fromAppToken($token);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * Build the Akeneo PIM client authenticated by token.
     *
     * @param string $clientId     Client id to use for the authentication
     * @param string $secret       Secret associated to the client
     * @param string $token        Token to use for the authentication
     * @param string $refreshToken Token to use to refresh the access token
     */
    public function buildAuthenticatedByToken(
        string $clientId,
        string $secret,
        string $token,
        string $refreshToken
    ): AkeneoPimClientInterface {
        $authentication = Authentication::fromToken($clientId, $secret, $token, $refreshToken);

        return $this->buildAuthenticatedClient($authentication);
    }

    /**
     * Enable Caching
     * Disabled by default
     */
    public function enableCache(): self
    {
        $this->cacheEnabled = true;

        return $this;
    }

    /**
     * Disable Caching
     * Disabled by default
     */
    public function disableCache(): self
    {
        $this->cacheEnabled = false;

        return $this;
    }

    protected function buildAuthenticatedClient(Authentication $authentication): AkeneoPimClientInterface
    {
        [$resourceClient, $pageFactory, $cursorFactory, $fileSystem] = $this->setUp($authentication);

        $resourceClientWithCache = $this->cacheEnabled ? new CachedResourceClient($resourceClient, new LRUCache()) : $resourceClient;

        return new AkeneoPimClient(
            $authentication,
            new ProductApi($resourceClient, $pageFactory, $cursorFactory),
            new CategoryApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new CategoryMediaFileApi($resourceClientWithCache),
            new AttributeApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new AttributeOptionApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new AttributeGroupApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new FamilyApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new ProductMediaFileApi($resourceClient, $pageFactory, $cursorFactory, $fileSystem),
            new LocaleApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new ChannelApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new CurrencyApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new MeasureFamilyApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new MeasurementFamilyApi($resourceClientWithCache),
            new AssociationTypeApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new FamilyVariantApi($resourceClientWithCache, $pageFactory, $cursorFactory),
            new ProductModelApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductModelDraftApi($resourceClient, $pageFactory, $cursorFactory),
            new PublishedProductApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductDraftApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetCategoryApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetTagApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetReferenceFileApi($resourceClient, $fileSystem),
            new AssetVariationFileApi($resourceClient, $fileSystem),
            new ReferenceEntityRecordApi($resourceClient, $pageFactory, $cursorFactory),
            new ReferenceEntityMediaFileApi($resourceClient, $fileSystem),
            new ReferenceEntityAttributeApi($resourceClient),
            new ReferenceEntityAttributeOptionApi($resourceClient),
            new ReferenceEntityApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetManagerApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetFamilyApi($resourceClient, $pageFactory, $cursorFactory),
            new AssetAttributeApi($resourceClient),
            new AssetAttributeOptionApi($resourceClient),
            new AssetMediaFileApi($resourceClient, $fileSystem),
            new ProductUuidApi($resourceClient, $pageFactory, $cursorFactory),
            new ProductDraftUuidApi($resourceClient, $pageFactory, $cursorFactory),
            new AppCatalogApi($resourceClient, $pageFactory, $cursorFactory),
            new AppCatalogProductApi($resourceClient, $pageFactory, $cursorFactory)
        );
    }

    protected function setUp(Authentication $authentication): array
    {
        $uriGenerator = new UriGenerator($this->baseUri);

        $httpClient = new HttpClient($this->getHttpClient(), $this->getRequestFactory(), $this->getStreamFactory(), $this->options);
        $authenticationApi = new AuthenticationApi($httpClient, $uriGenerator);

        if (
            null === $authentication->getAccessToken()
            && null !== $authentication->getUsername()
            && null !== $authentication->getPassword()
        ) {
            $tokens = $authenticationApi->authenticateByPassword(
                $authentication->getClientId(),
                $authentication->getSecret(),
                $authentication->getUsername(),
                $authentication->getPassword()
            );

            $authentication
                ->setAccessToken($tokens['access_token'])
                ->setRefreshToken($tokens['refresh_token']);
        }

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

    private function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    private function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    private function getStreamFactory(): StreamFactoryInterface
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }
}
