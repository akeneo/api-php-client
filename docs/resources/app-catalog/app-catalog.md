### App catalog


#### Get the list of owned catalogs
::: php-client-availability versions=9.1

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('https://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like the following:
 * [
 *  {
 *      "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f2",
 *      "name": "Store FR",
 *      "enabled": true
 *  },
 * {
 *      "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f3",
 *      "name": "Store US",
 *      "enabled": true
 *  }
 * ]
 */
$catalogs = $client->getAppCatalogApi()->all();
```


#### Get a catalog
::: php-client-availability versions=9.1

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('https://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an object like the following :
 *  {
 *      "id": "12351d98-200e-4bbc-aa19-7fdda1bd14f2",
 *      "name": "Store FR",
 *      "enabled": true
 *  }
 */
$catalogs = $client->getAppCatalogApi()->get('12351d98-200e-4bbc-aa19-7fdda1bd14f2');
```

#### Create a new catalog
::: php-client-availability versions=9.1

If the catalog does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAppCatalogApi()->create(['name' => 'A catalog name']);
```


#### Update a catalog
::: php-client-availability versions=9.1

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAppCatalogApi()->upsert(['name' => 'A catalog name']);
```


#### Delete a catalog
::: php-client-availability versions=9.1

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAppCatalogApi()->delete('12351d98-200e-4bbc-aa19-7fdda1bd14f2');
```
