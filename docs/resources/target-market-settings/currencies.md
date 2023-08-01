### Currency

#### Get a currency
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'    => 'EUR',
 *     'enabled' => true,
 * ]
 */
 $currency = $client->getCurrencyApi()->get('EUR');
```

#### Get a list of currencies
::: php-client-availability all-versions

There are two ways of getting currencies.
 
**By getting page**

This method allows to get currencies page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getCurrencyApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the currencies. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$currencies = $client->getCurrencyApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).
