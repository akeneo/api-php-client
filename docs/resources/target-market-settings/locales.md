### Locale

#### Get a locale
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => 'en_US',
 *     'enable' => true,
 * ]
 */
$locale = $client->getLocaleApi()->get('en_US');
```

#### Get a list of locales
::: php-client-availability all-versions

There are two ways of getting locales.

**By getting pages**

This method allows to get locales page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getLocaleApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the locales. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$locales = $client->getLocaleApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).
