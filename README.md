# PHP Akeneo PIM API

A simple PHP client to use the [Akeneo PIM API](https://api.akeneo.com/).

Matrix compatibility:

| PIM version(s) | API PHP Client version | CI status                                                                                                                                         |
|----------------|------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------|
| v2.0           | v1.0                   | [![Build Status](https://travis-ci.org/akeneo/api-php-client.svg?branch=1.0)](https://travis-ci.org/akeneo/api-php-client)                        |
| v2.1 - v2.2    | v2.0                   | [![Build Status](https://travis-ci.org/akeneo/api-php-client.svg?branch=2.0)](https://travis-ci.org/akeneo/api-php-client)                        |
| v2.3           | v3.0                   | [![Build Status](https://travis-ci.org/akeneo/api-php-client.svg?branch=3.0)](https://travis-ci.org/akeneo/api-php-client)                        |
| v3.0 - v4.0    | v4.0 - v5.0            | [![Build Status](https://travis-ci.org/akeneo/api-php-client.svg?branch=4.0)](https://travis-ci.org/akeneo/api-php-client)                        |
| v5.0           | v6.0                   | -                                                                                                                                                 |
| v6.0           | v7.0 to v9.1           | -                                                                                                                                                 |
| -              | master                 | [![CircleCI](https://circleci.com/gh/akeneo/api-php-client/tree/master.svg?style=svg)](https://circleci.com/gh/akeneo/api-php-client/tree/master) |

Note that our PHP client is backward compatible.
For example, if your PIM is currently a v2.3, you can still use a 1.0 version of the PHP client. The new endpoints available in v2.3 will not be available in the v1.0 of the PHP client.

## Requirements

* PHP >= 7.4
* Composer 

## Installation

We use HTTPPlug as the HTTP client abstraction layer.
In this example, we will use [Guzzle](https://github.com/guzzle/guzzle) v6 as the HTTP client implementation.

`api-php-client` uses [Composer](http://getcomposer.org).
The first step to use `api-php-client` is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then, run the following command to require the library:
```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter:^2.0 http-interop/http-factory-guzzle:^1.0
```

If you want to use another HTTP client implementation, you can check [here](https://packagist.org/providers/php-http/client-implementation) the full list of HTTP client implementations. 

## Documentation

Full documentation is available on the [API website](https://api.akeneo.com/php-client/introduction.html).

## Getting started

### Initialise the client
You first need to initialise the client with your credentials client id/secret and with your user/password.

If you don't have any client id, let's take a look at [this page](https://api.akeneo.com/documentation/authentication.html) to create it.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
```

You can authenticate to the client with your token/refresh token as well.
```php
$client = $clientBuilder->buildAuthenticatedByToken('client_id', 'secret', 'token', 'refresh_token');
```

Getting the token and refresh token is as simple as:
```php
$client->getToken();
$client->getRefreshToken();
```

If you are developing an App, authenticate with your app token.
```php
$client = $clientBuilder->buildAuthenticatedByAppToken('app_token');
```

### Get a product

```php
$product = $client->getProductApi()->get('top');
echo $product['identifier']; // display "top"
```

### Get a list of products

#### By getting pages

```php
$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder->addFilter('enabled', '=', true);
$searchFilters = $searchBuilder->getFilters();

$firstPage = $client->getProductApi()->listPerPage(50, true, ['search' => $searchFilters]);

echo $firstPage->getCount();

foreach ($firstPage->getItems() as $product) {
    // do your stuff here
    echo $product['identifier'];
}

$nextPage = $firstPage->getNextPage();

$firstPage = $nextPage->getPreviousPage();
```

#### By getting a cursor 

```php
$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder->addFilter('enabled', '=', true);
$searchFilters = $searchBuilder->getFilters();

$products = $client->getProductApi()->all(50, ['search' => $searchFilters]);
foreach ($products as $product) {
    // do your stuff here
    echo $product['identifier'];
}
```

### Create a product

```php
$client->getProductApi()->create('top', ['enabled' => true]);
```

### Upsert a product

```php
$client->getProductApi()->upsert('top', ['family' => 'tshirt']);
```

### Upsert a list of of products

```php
$client->getProductApi()->upsertList([
    [
        'identifier' => 'top',
        'family' => 'tshirt',
    ],
    [
        'identifier' => 'cap',
        'categories' => ['hat'],
    ],
]);
```

### Headers option

You can make the client send requests with additional headers. Default client headers can be overriden.

```php
$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder(
    'http://localhost',
    ['headers' => ['X-HEADER-NAME' => 'content']]
);
$client = $clientBuilder->buildAuthenticatedByToken('token');
```

## Testing

Do note that you have to delete the `composer.lock` because Doctrine dependencies are loaded.
These dependencies are different in function of the PHP version running `composer install`.

``` bash
# Build the project
make dependencies

# Run PHPUnit tests
make unit

# Run PHPSpec tests
make spec

# Run code style check
make cs

# ... or directly run all tests
make tests
```

## Support

The support of this client is made in best effort by our Akeneo team.

If you find a bug or want to submit an improvement, don't hesitate to raise an issue on Github.
Also, you can ask questions and discuss about the PHP client with the community in the [Slack User Group](https://akeneopim-ug.slack.com/messages/web-api/).

## Contributing

As this PHP client is an open-source project, all contributions are very welcome!

For more information, please consult [the contributing section](CONTRIBUTING.md)
