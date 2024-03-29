<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\Routing\UriGeneratorInterface;
use Akeneo\Pim\ApiClient\Stream\MultipartStreamBuilderFactory;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponseFactory;
use GuzzleHttp\Promise\PromiseInterface;
use Http\Promise\Promise;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Generic client to execute common request on resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceClient implements ResourceClientInterface
{
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected UriGeneratorInterface $uriGenerator,
        protected MultipartStreamBuilderFactory $multipartStreamBuilderFactory,
        protected UpsertResourceListResponseFactory $upsertListResponseFactory
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getResource(string $uri, array $uriParameters = [], array $queryParameters = []): array
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters, $queryParameters);

        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(
        string $uri,
        array $uriParameters = [],
        ?int $limit = 100,
        ?bool $withCount = false,
        array $queryParameters = []
    ): array {
        if (array_key_exists('limit', $queryParameters)) {
            throw new InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters');
        }

        if (array_key_exists('with_count', $queryParameters)) {
            throw new InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters');
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
    public function createResource(string $uri, array $uriParameters = [], array $body = []): int
    {
        $response = $this->sendCreateRequest($uri, $uriParameters, $body);

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function createAndReturnResource(string $uri, array $uriParameters = [], array $body = []): array
    {
        $response = $this->sendCreateRequest($uri, $uriParameters, $body);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function createMultipartResource(string $uri, array $uriParameters = [], array $requestParts = []): ResponseInterface
    {
        $streamBuilder = $this->multipartStreamBuilderFactory->create();

        foreach ($requestParts as $requestPart) {
            if (!isset($requestPart['name']) || !isset($requestPart['contents'])) {
                throw new InvalidArgumentException('The keys "name" and "contents" must be defined for each request part');
            }

            $options = isset($requestPart['options']) ? $requestPart['options'] : [];
            $streamBuilder->addResource($requestPart['name'], $requestPart['contents'], $options);
        }

        $multipartStream = $streamBuilder->build();
        $boundary = $streamBuilder->getBoundary();
        $headers = ['Content-Type' => sprintf('multipart/form-data; boundary="%s"', $boundary)];
        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        return $this->httpClient->sendRequest('POST', $uri, $headers, $multipartStream);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertResource(string $uri, array $uriParameters = [], array $body = []): int
    {
        $response = $this->sendUpsertRequest($uri, $uriParameters, $body);

        return $response->getStatusCode();
    }

    public function upsertAsyncResource(
        string $uri,
        array $uriParameters = [],
        array $body = []
    ): PromiseInterface|Promise {
        return $this->sendAsyncUpsertRequest($uri, $uriParameters, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertAndReturnResource(string $uri, array $uriParameters = [], array $body = []): array
    {
        $response = $this->sendUpsertRequest($uri, $uriParameters, $body);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function upsertAsyncAndReturnPromise(
        string $uri,
        array $uriParameters = [],
        array $body = []
    ): PromiseInterface|Promise {
        return $this->sendAsyncUpsertRequest($uri, $uriParameters, $body);
    }

    public function prepareResourceListRequest($resources = []): StreamInterface|string
    {
        if (!is_array($resources) && !$resources instanceof StreamInterface) {
            throw new InvalidArgumentException('The parameter "resources" must be an array or an instance of StreamInterface.');
        }

        if (is_array($resources)) {
            $body = '';
            $isFirstLine = true;
            foreach ($resources as $resource) {
                if (!is_array($resource)) {
                    throw new InvalidArgumentException('The parameter "resources" must be an array of array.');
                }
                unset($resource['_links']);
                $body .= ($isFirstLine ? '' : PHP_EOL) . json_encode($resource);
                $isFirstLine = false;
            }
        } else {
            $body = $resources;
        }

        return $body;
    }

    /**
     * {@inheritdoc}
     */
    public function upsertStreamResourceList(string $uri, array $uriParameters = [], $resources = []): \Traversable
    {
        $body = $this->prepareResourceListRequest($resources);
        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/vnd.akeneo.collection+json'],
            $body
        );

        return $this->upsertListResponseFactory->create($response->getBody());
    }

    public function upsertAsyncStreamResourceList(
        string $uri,
        array $uriParameters = [],
        $resources = []
    ): PromiseInterface|Promise {
        $body = $this->prepareResourceListRequest($resources);
        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        return $this->httpClient->sendAsync(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/vnd.akeneo.collection+json'],
            $body
        );
    }

    /**
     * {@inheritdoc}
     */
    public function upsertJsonResourceList(string $uri, array $uriParameters = [], array $resources = []): array
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        $response = $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($resources)
        );

        $response = json_decode($response->getBody()->getContents(), true);
        if (!is_array($response)) {
            throw new RuntimeException('The server response is not a valid JSON');
        }

        return $response;
    }

    public function upsertAsyncJsonResourceList(
        string $uri,
        array $uriParameters = [],
        array $resources = []
    ): PromiseInterface|Promise {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);
        return $this->httpClient->sendAsync(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($resources)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteResource(string $uri, array $uriParameters = []): int
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        $response = $this->httpClient->sendRequest('DELETE', $uri);

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamedResource(string $uri, array $uriParameters = []): ResponseInterface
    {
        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        return $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);
    }

    private function sendCreateRequest(string $uri, array $uriParameters = [], array $body = []): ResponseInterface
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        return $this->httpClient->sendRequest(
            'POST',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );
    }

    private function sendUpsertRequest(string $uri, array $uriParameters = [], array $body = []): ResponseInterface
    {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        return $this->httpClient->sendRequest(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );
    }

    private function sendAsyncUpsertRequest(
        string $uri,
        array $uriParameters = [],
        array $body = []
    ): PromiseInterface|Promise {
        unset($body['_links']);

        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        return $this->httpClient->sendAsync(
            'PATCH',
            $uri,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );
    }
}
