### Asset attribute
We refer here to the asset attribute option of the [Asset Manager](/concepts/asset-manager.html#asset-attribute).

#### Get an attribute of a given asset family
::: php-client-availability versions=5.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code' => 'label',
 *     'labels' => [],
 *     'type' => 'text',
 *     'value_per_locale' => true,
 *     'value_per_channel' => false,
 *     'is_required_for_completeness' => false,
 *     'max_characters' => NULL,
 *     'is_textarea' => false,
 *     'is_rich_text_editor' => false,
 *     'validation_rule' => 'none',
 *     'validation_regexp' => NULL,
 * ]
*/
$client->getAssetAttributeApi()->get('user_instructions', 'label');
```

#### Get the list of attributes of a given asset
::: php-client-availability versions=5.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetAttributeApi()->all('user_instructions');
```

#### Upsert an attribute of a given asset family
::: php-client-availability versions=5.0 ee-only

If the attribute does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetAttributeApi()->upsert('user_instructions', 'label', [
    'code' => 'label',
    'labels' => [
        'en_US' => 'Label',
    ],
    'type' => 'text'
]);
```
