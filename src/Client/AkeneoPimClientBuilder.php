<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\AuthenticationApi;
use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\HttpClient\AuthenticatedHttpClient;
use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\Pagination\PageFactory;
use Akeneo\Pim\Pagination\ResourceCursorFactory;
use Akeneo\Pim\Routing\UriGenerator;
use Akeneo\Pim\Security\Authentication;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Http\Client\HttpClient as Client;

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

    /** @var Authentication */
    protected $authentication;

    /** @var array */
    protected $options;

    /** @var Client */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var StreamFactory */
    protected $streamFactory;

    /**
     * @param string $baseUri  Base uri to request the API
     * @param string $clientId Client id to use for the authentication
     * @param string $secret   Secret associated to the client
     * @param string $username Username to use for the authentication
     * @param string $password Password associated to the username
     * @param array  $options  Option to customize Akeneo PIM Client
     */
    public function __construct($baseUri, $clientId, $secret, $username, $password, $options = [])
    {
        $this->baseUri = $baseUri;
        $this->authentication = new Authentication($clientId, $secret, $username, $password);
        $this->httpClient = isset($options['http_client']) ? $options['http_client'] : HttpClientDiscovery::find();
        $this->requestFactory = isset($options['request_factory']) ? $options['request_factory'] : MessageFactoryDiscovery::find();
        $this->streamFactory = isset($options['stream_factory']) ? $options['stream_factory'] : StreamFactoryDiscovery::find();
    }

    /**
     * Build the Akeneo PIM client.
     *
     * @return AkeneoPimClientInterface
     */
    public function build()
    {
        $uriGenerator = new UriGenerator($this->baseUri);

        $httpClient = new HttpClient($this->httpClient, $this->requestFactory);
        $authenticationApi = new AuthenticationApi($httpClient, $uriGenerator);
        $authenticatedHttpClient = new AuthenticatedHttpClient($httpClient, $authenticationApi, $this->authentication);

        $pageFactory = new PageFactory($authenticatedHttpClient);
        $resourceClient = new ResourceClient($authenticatedHttpClient, $uriGenerator, $pageFactory);

        $cursorFactory = new ResourceCursorFactory();

        $client = new AkeneoPimClient(new CategoryApi($resourceClient, $pageFactory, $cursorFactory));

        return $client;
    }
}
