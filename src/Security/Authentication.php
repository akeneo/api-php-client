<?php

namespace Akeneo\Pim\ApiClient\Security;

/**
 * Credential data to authenticate to the API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Authentication
{
    /** @var string */
    protected $clientId;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $refreshToken;

    protected function __construct()
    {
    }

    public static function fromPassword(string $clientId, string $secret, string $username, string $password): Authentication
    {
        $authentication = new static();
        $authentication->clientId = $clientId;
        $authentication->secret = $secret;
        $authentication->username = $username;
        $authentication->password = $password;

        return $authentication;
    }

    public static function fromToken(string $clientId, string $secret, string $accessToken, string $refreshToken): Authentication
    {
        $authentication = new static();
        $authentication->clientId = $clientId;
        $authentication->secret = $secret;
        $authentication->accessToken = $accessToken;
        $authentication->refreshToken = $refreshToken;

        return $authentication;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
