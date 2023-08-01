### Asset family

We refer here to the asset family of the [Asset Manager](/concepts/asset-manager.html#asset-family).

#### Get an asset family
::: php-client-availability versions=5.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code' => 'user_instructions',
 *     'labels' => [
 *         'en_US' => 'User instructions',
 *         'fr_FR' => 'Notice d\'utilisation',
 *     ],
 *     'product_link_rules' => [],
 * ]
 */
$client->getAssetFamilyApi()->get('user_instructions');
```
 
#### Get the list of the asset families
::: php-client-availability versions=5.0 ee-only

You can get more information about the available query parameters [here](/api-reference.html#get_asset_families).

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetFamilyApi()->all();
```

#### Upsert an asset family
::: php-client-availability versions=5.0 ee-only

If the asset family does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetFamilyApi()->upsert('user_instructions', [
    'code' => 'user_instructions',
    'labels' => [
        'en_US' => 'User instructions',
        'fr_FR' => 'Notice d\'utilisation',
    ]
]);
```
