<?php

namespace Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Api\AuthenticationApiInterface;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Security\Authentication;
use Psr\Http\Message\ResponseInterface;

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
    public function sendRequest(string $httpMethod, $uri, array $headers = [], $body = null): ResponseInterface
    {
        if (null === $this->authentication->getAccessToken()) {
            $tokens = $this->authenticationApi->authenticateByPassword(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->authentication->getUsername(),
                $this->authentication->getPassword()
            );

            $this->authentication
                ->setAccessToken($tokens['access_token'])
                ->setRefreshToken($tokens['refresh_token']);
        }

        try {
            $headers['Authorization'] =  sprintf('Bearer %s', $this->authentication->getAccessToken());
            $response = $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        } catch (UnauthorizedHttpException $e) {
            $tokens = $this->authenticationApi->authenticateByRefreshToken(
                $this->authentication->getClientId(),
                $this->authentication->getSecret(),
                $this->authentication->getRefreshToken()
            );

            $this->authentication
                ->setAccessToken($tokens['access_token'])
                ->setRefreshToken($tokens['refresh_token']);

            $headers['Authorization'] =  sprintf('Bearer %s', $this->authentication->getAccessToken());
            $response =  $this->basicHttpClient->sendRequest($httpMethod, $uri, $headers, $body);
        }

        return $response;
    }
}
