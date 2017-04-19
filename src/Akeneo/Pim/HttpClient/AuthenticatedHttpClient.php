<?php

namespace Akeneo\Pim\HttpClient;

use Akeneo\Pim\Api\AuthenticationApiInterface;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\Security\Authentication;

/**
 * Http client to send an authenticated request.
 *
 * The authentication process is entirely handle by this client implementation.
 *
 * It enriches the request with an access token.
 * If the access token is expired, it will automatically refresh it.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AuthenticatedHttpClient implements HttpClientInterface
{
    /** @var HttpClient */
    protected $basicHttpClient;

    /** @var AuthenticationApiInterface */
    protected $authenticationApi;

    /** @var Authentication */
    protected $authentication;

    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $refreshToken;


    /**
     * @param HttpClient                 $basicHttpClient
     * @param AuthenticationApiInterface $authenticationApi
     * @param Authentication             $authentication
     */
    public function __construct(
        HttpClient $basicHttpClient,
        AuthenticationApiInterface $authenticationApi,
        Authentication $authentication
    ) {
        $this->httpClient = $basicHttpClient;
        $this->authenticationApi = $authenticationApi;
        $this->authentication = $authentication;

    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest($httpMethod, $uri, array $headers = [], $body = null)
    {
        if (null === $this->accessToken) {
            $tokens = $this->authenticationApi->authenticateByPassword(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->authentication->getUsername(),
                $this->authentication->getPassword()
            );
            $this->accessToken = $tokens['access_token'];
            $this->refreshToken = $tokens['refresh_token'];
        }

        try {
            $headers['Authorization'] =  sprintf('Bearer %s', $this->accessToken);
            $response = $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        } catch (UnauthorizedHttpException $e) {
            $tokens = $this->authenticationApi->authenticateByRefreshToken($this->refreshToken);
            $this->accessToken = $tokens['access_token'];
            $this->refreshToken = $tokens['refresh_token'];

            $headers['Authorization'] =  sprintf('Bearer %s', $this->accessToken);
            $response = $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        }

        return $response;
    }

    /**
     * Returns the access token. If the access token is missing, it performs a request
     * in order to get it.
     *
     * @return string
     */
    protected function getAccessToken()
    {
        if (null !== $this->accessToken) {
            return $this->accessToken;
        }

        $body = [
            'grant_type' => 'password',
            'username'   => $this->authentication->getUsername(),
            'password'   => $this->authentication->getPassword(),
        ];

        return $this->authenticate($body);
    }

    /**
     * Returns the access token after being refreshed.
     *
     * @return string
     */
    protected function getRefreshedAccessToken()
    {
        $body = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        return $this->authenticate($body);
    }

    /**
     * Authenticates the client by requesting the access token and the refresh token.
     *
     * @param array $body body of the request
     *
     * @return string returns the new access token
     */
    protected function authenticate(array $body)
    {
        $headers = [
            'Authorization' => sprintf(
                'Basic %s',
                base64_encode($this->authentication->getClientId() . ':' . $this->authentication->getSecret())
            ),
            'Content-Type'  => 'application/json',
        ];

        $uri = $this->uriGenerator->generate('api/oauth/v1/token');
        $request = $this->requestFactory->createRequest('POST', $uri, $headers, json_encode($body));

        $response = $this->httpClient->sendRequest($request);

        $this->httpExceptionHandler->handle($response, [200]);

        $responseContent = json_decode($response->getBody()->getContents(), true);

        $this->accessToken = $responseContent['access_token'];
        $this->refreshToken = $responseContent['refresh_token'];

        return $this->accessToken;
    }
}
