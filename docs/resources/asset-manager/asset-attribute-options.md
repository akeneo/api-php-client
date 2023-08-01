### Asset attribute option

We refer here to the asset attribute option of the [Asset Manager](/concepts/asset-manager.html#asset-attribute-option).

#### Get an attribute option for a given attribute of a given asset
::: php-client-availability versions=5.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code' => 'blue',
 *     'labels' => [
 *         'en_US' => 'Blue',
 *         'fr_FR' => 'Bleu',
 *     ]
 * ]
 */
$client->getAssetAttributeOptionApi()->get('packshot', 'main_colors', 'blue);

```

#### Get the list of attribute options of a given attribute for a given asset
::: php-client-availability versions=5.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetAttributeOptionApi()->all('packshot', 'main_colors');
```

#### Upsert an attribute option for a given attribute of a given asset
::: php-client-availability versions=5.0 ee-only

If the attribute option does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetAttributeOptionApi()->upsert('packshot', 'main_colors', 'blue', [
    'code' => 'blue',
    'labels' => [
        'en_US' => 'Blue',
        'fr_FR' => 'Bleu',
    ]
]);
```
