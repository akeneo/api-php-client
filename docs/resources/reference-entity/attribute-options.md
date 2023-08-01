### Reference entity attribute option

#### Get an attribute option for a given attribute of a given reference entity
::: php-client-availability versions=4.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => 'red',
 *     'labels' => [
 *         'en_US' => 'Red',
 *         'fr_FR' => 'Rouge',
 *     ]
 * ]
 */
$referenceEntityAttributeOption = $client->getReferenceEntityAttributeOptionApi()->get('designer', 'favorite_color', 'red');
```

#### Get the list of attribute options of a given attribute for a given reference entity
::: php-client-availability versions=4.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$referenceEntityAttributeOptions = $client->getReferenceEntityAttributeOptionApi()->all('designer', 'favorite_color');
```

#### Upsert an attribute option for a given attribute of a given reference entity
::: php-client-availability versions=4.0 ee-only

If the attribute option does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getReferenceEntityAttributeOptionApi()->upsert('designer', 'favorite_color', 'blue', [
    'code' => 'blue',
    'labels' => [
        'en_US' => 'Blue',
        'fr_FR' => 'Bleu',
    ]
]);
```
