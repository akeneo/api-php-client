### App catalog product


#### Get the list of product uuids
::: php-client-availability versions=9.1

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('https://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like the following:
 * [
 *  "844c736b-a19b-48a6-a354-6056044729f0",
 *  "b2a683ef-4a91-4ed3-b3fa-76dab065a8d5",
 *  "eddfbd2a-abc7-488d-b9e3-41289c824f80"
 * ]
 */
$catalogs = $client->getAppCatalogProductApi()->all();
```
