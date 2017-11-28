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

    /**
     * @param string $clientId
     * @param string $secret
     * @param string $username
     * @param string $password
     *
     * @return Authentication
     */
    public static function fromPassword($clientId, $secret, $username, $password)
    {
        $authentication = new static();
        $authentication->clientId = $clientId;
        $authentication->secret = $secret;
        $authentication->username = $username;
        $authentication->password = $password;

        return $authentication;
    }

    /**
     * @param string $clientId
     * @param string $secret
     * @param string $accessToken
     * @param string $refreshToken
     *
     * @return Authentication
     */
    public static function fromToken($clientId, $secret, $accessToken, $refreshToken)
    {
        $authentication = new static();
        $authentication->clientId = $clientId;
        $authentication->secret = $secret;
        $authentication->accessToken = $accessToken;
        $authentication->refreshToken = $refreshToken;

        return $authentication;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return null|string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $accessToken
     *
     * @return Authentication
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $refreshToken
     *
     * @return Authentication
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
