<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClientInterface;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use Akeneo\Pim\Stream\UpsertListResourcesResponse;
use Http\Message\StreamFactory;

/**
 * Generic client to execute common request on resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceClient implements ResourceClientInterface
{
    /** @var HttpClientInterface */
    protected $httpClient;

    /** @var UriGeneratorInterface */
    protected $uriGenerator;

    /** @var  StreamFactory */
    protected $streamFactory;

    /**
     * @param HttpClientInterface   $httpClient
     * @param UriGeneratorInterface $uriGenerator
     * @param StreamFactory         $streamFactory
     */
    public function __construct(
        HttpClientInterface $httpClient,
        UriGeneratorInterface $uriGenerator,
        StreamFactory $streamFactory
    ) {
        $this->httpClient = $httpClient;
        $this->uriGenerator = $uriGenerator;
        $this->streamFactory = $streamFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource($uri, array $uriParameters = [], array $queryParameters = [])
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters, $queryParameters);
        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(
        $uri,
        array $uriParameters = [],
        $limit = 10,
        $withCount = false,
        array $queryParameters = []
    ) {
        if (array_key_exists('limit', $queryParameters)) {
            throw new \InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters');
        }

        if (array_key_exists('with_count', $queryParameters)) {
            throw new \InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters');
        }

        if (null !== $limit) {
            $queryParameters['limit'] = $limit;
        }

        if (null !== $withCount) {
            $queryParameters['with_count'] = $withCount;
        }

        return $this->getResource($uri, $uriParameters, $queryParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createResource($uri, array $uriParameters = [], array $body = [])
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'POST',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateResource($uri, array $uriParameters = [], array $body = [])
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateResources($uri, array $uriParameters = [], $resources = [])
    {
        $streamFunction = function() use ($resources) {
            $isFirstLine = true;
            foreach ($resources as $resource) {
                unset($resource['_links']);
                yield ($isFirstLine ? '' : PHP_EOL) . json_encode($resource);
                $isFirstLine = false;
            }
        };

        $streamedBody = $this->streamFactory->createStream($streamFunction());

        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/vnd.akeneo.collection+json'],
            $streamedBody
        );

        return new UpsertListResourcesResponse($response->getBody());
    }
}
