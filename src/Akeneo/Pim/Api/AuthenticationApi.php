<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\HttpClient\HttpClient;
use Akeneo\Pim\Routing\UriGeneratorInterface;

/**
 * API implementation to manage the authentication.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AuthenticationApi implements AuthenticationApiInterface
{
    /** @var HttpClient */
    protected $httpClient;

    /** @var UriGeneratorInterface */
    protected $uriGenerator;

    /**
     * @param HttpClient            $httpClient
     * @param UriGeneratorInterface $uriGenerator
     */
    public function __construct(HttpClient $httpClient, UriGeneratorInterface $uriGenerator)
    {
        $this->httpClient = $httpClient;
        $this->uriGenerator = $uriGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateByPassword($clientId, $secret, $username, $password)
    {
        $requestBody = [
            'grant_type' => 'password',
            'username'   => $username,
            'password'   => $password,
        ];

        return $this->authenticate($requestBody);
    }

    /**
     * {@inheritdoc}
     */
    public function authenticateByRefreshToken($refreshToken)
    {
        $requestBody = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        return $this->authenticate($requestBody);
    }

    /**
     * Authenticates the client by requesting the access token and the refresh token.
     *
     * @param string $clientId    client id
     * @param string $secret      secret associated to the client id
     * @param array  $requestBody body of the request to authenticate
     *
     * @return string returns the body of the response containing access token and refresh token
     */
    protected function authenticate($clientId, $secret, array $requestBody)
    {
        $headers = [
            'Authorization' => sprintf('Basic %s', base64_encode($clientId . ':' . $secret)),
            'Content-Type'  => 'application/json',
        ];

        $uri = $this->uriGenerator->generate('api/oauth/v1/token');

        $response = $this->httpClient->sendRequest('POST', $uri, $headers, $requestBody);

        $responseBody = json_decode($response->getBody()->getContents(), true);

        return $responseBody;
    }
}
