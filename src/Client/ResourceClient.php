<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClientInterface;
use Akeneo\Pim\MultipartStream\MultipartStreamBuilderFactory;
use Akeneo\Pim\Routing\UriGeneratorInterface;

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

    /** @var MultipartStreamBuilderFactory */
    protected $multipartStreamBuilderFactory;

    /**
     * @param HttpClientInterface           $httpClient
     * @param UriGeneratorInterface         $uriGenerator
     * @param MultipartStreamBuilderFactory $multipartStreamBuilderFactory
     */
    public function __construct(
        HttpClientInterface $httpClient,
        UriGeneratorInterface $uriGenerator,
        MultipartStreamBuilderFactory $multipartStreamBuilderFactory
    ) {
        $this->httpClient = $httpClient;
        $this->uriGenerator = $uriGenerator;
        $this->multipartStreamBuilderFactory = $multipartStreamBuilderFactory;
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
    public function createMultipartResource($uri, array $uriParameters = [], array $requestParts = [])
    {
        $streamBuilder =  $this->multipartStreamBuilderFactory->create();

        foreach ($requestParts as $requestPart) {
            if (!isset($requestPart['name']) || !isset($requestPart['contents'])) {
                throw new \InvalidArgumentException('The keys "name" and "contents" must be defined for each request part');
            }

            $options = isset($requestPart['options']) ? $requestPart['options'] : [];
            $streamBuilder->addResource($requestPart['name'], $requestPart['contents'], $options);
        }

        $multipartStream = $streamBuilder->build();
        $boundary = $streamBuilder->getBoundary();
        $headers = ['Content-Type' => sprintf('multipart/form-data; boundary="%s"', $boundary)];
        $uri = $this->uriGenerator->generate($uri, $uriParameters);

        $response = $this->httpClient->sendRequest('POST', $uri, $headers, $multipartStream);

        return $response->getStatusCode();
    }

    /**
     * {@inheritdoc}
     */
    public function upsertResource($uri, array $uriParameters = [], array $body = [])
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
}
