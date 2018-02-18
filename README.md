# PHP Akeneo PIM API

A simple PHP client to use the [Akeneo PIM API](https://api.akeneo.com/). 
It is compatible with the 2.0 of our dear Akeneo PIM.

## Requirements

* PHP >= 5.6
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
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter
```

If you want to use another HTTP client implementation, you can check [here](https://packagist.org/providers/php-http/client-implementation) the full list of HTTP client implementations. 

## Documentation

Full documentation is available on the [API website](https://api.akeneo.com/php-client/introduction.html).

## Getting started

### Initialise the client
You first need to initialise the client with your credentials client id/secret and with your user/password.

If you don't have any client id, let's take a look at [this page](https://api.akeneo.com/documentation/security.html#authentication) to create it.

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

echo $page->getCount();

foreach ($page->getItems() as $product) {
    // do your stuff here
    echo $product['identifier'];
}

$nextPage = $page->getNextPage();

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

## Support

The support of this client is made in best effort by our Akeneo team.

If you find a bug or want to submit an improvement, don't hesitate to raise an issue on Github.
Also, you can ask questions and discuss about the PHP client with the community in the [Slack User Group](https://akeneopim-ug.slack.com/messages/web-api/).
