<?php

namespace Akeneo\Pim\HttpClient;

use Akeneo\Pim\Api\AuthenticationApiInterface;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\Security\Authentication;

/**
 * Http client to send an authenticated request.
 *
 * The authentication process is automatically handle by this client implementation.
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
        $this->basicHttpClient = $basicHttpClient;
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
            // TODO : cannot work without PIM-6387
            $tokens = $this->authenticationApi->authenticateByRefreshToken(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->refreshToken
            );

            $this->accessToken = $tokens['access_token'];
            $this->refreshToken = $tokens['refresh_token'];

            $headers['Authorization'] =  sprintf('Bearer %s', $this->accessToken);
            $response = $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        }

        return $response;
    }
}
