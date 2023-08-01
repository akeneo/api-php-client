# Authentication

You can be authenticated to the REST API either by providing a username and a password or by providing a token and a refresh token.

## By App token

Authenticate with the access token received from an [OAuth authorization](/apps/authentication-and-authorization.html#token-success-response).

```php
<?php

require_once '/vendor/autoload.php';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByAppToken('token');
```

## By username/password

You can authenticate to the client with your credentials client id/secret and your user/password:

```php
<?php

require_once '/vendor/autoload.php';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
```

Then, every client's request is automatically authenticated.
If you don't have any client id, please take a look at [this page](/documentation/authentication.html#client-idsecret-generation) to create it.

This is the easiest way to authenticate the client.

## By token/refresh token

The main drawback of the authentication by password is that it requests a new token before doing any call to the REST API. Therefore, it can decrease the performance if you use the PHP client in an application that creates a lot of processes.
For example, in a PHP web application, a new PHP process is created for each request. If would be time consuming to get a new token for each process, in order to be authenticated.

In this context, it's better to use the same token for each new request.

That's why you can create a client with the couple token/refresh token instead of username/password.

```php
<?php

require_once '/vendor/autoload.php';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByToken('client_id', 'secret', 'token', 'refresh_token');
```

To get the couple token/refresh token, you just have to do:
```php
$client->getToken();
$client->getRefreshToken();
```

::: warning
It's your responsibility to store the token and the refresh token.
For example, it can be stored in a file or in a database.
:::

### Example

This is a very basic example to put token and refresh token into a file, in order to be shared with other processes:

```php
<?php

require_once '/vendor/autoload.php';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
if (!file_exists('/tmp/akeneo_token.tmp')) {
    $client = $clientBuilder->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
} else {
    $credentials = file_get_contents('/tmp/akeneo_token.tmp');
    list($token, $refreshToken) = explode(':', $credentials);
    $client = $clientBuilder->buildAuthenticatedByToken('client_id', 'secret', $token, $refreshToken);
}

$category = $client->getCategoryApi()->get('master');

file_put_contents('/tmp/akeneo_token.tmp', $client->getToken() . ':' . $client->getRefreshToken());
```
