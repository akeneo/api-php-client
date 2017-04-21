<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClient;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ResourceClient implements ResourceClientInterface
{
    /** @var HttpClient */
    protected $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource($uri, array $headers = [])
    {
        $response = $this->httpClient->sendRequest('GET', $uri, $headers);

        return json_decode($response->getBody()->getContents(), true);
    }
}
