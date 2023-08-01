### Family variant

#### Get a family variant
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
                     
/*
 * Returns an array like this:
 * [
 *     'code' => 'boots_color_size',
 *     'labels' => [
 *         'de_DE' => 'Stiefel nach Farbe und Größe',
 *         'en_US' => 'Boots by color and size',
 *         'fr_FR' => 'Bottes par couleur et taille'
 *     ],
 *     'variant_attribute_sets' => [
 *         [
 *             'level' => 1,
 *             'axes' => ['color'],
 *             'attributes' => ['name', 'description', 'color']
 *         ],
 *         [
 *             'level' => 2,
 *             'axes' => ['size'],
 *             'attributes' => ['sku', 'size']
 *         ]
 *     ]
 * ]
 */
$familyVariant = $client->getFamilyVariantApi()->get('boots', 'boots_color_size');
```

#### Create a family variant
::: php-client-availability all-versions

If the family variant does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getFamilyVariantApi()->create('boots', 'boots_size_color', [
    'labels' => [
        'en_US' => 'Boots by color and size'
    ],
    'variant_attribute_sets' => [
        [
            'level' => 1,
            'axes' => ['size'],
            'attributes' => ['name', 'description', 'size']
        ],
        [
            'level' => 2,
            'axes' => ['color'],
            'attributes' => ['sku', 'color']
        ]
    ]
]);
```

#### Get a list of family variants
::: php-client-availability all-versions

There are two ways of getting family variants.

**By getting pages**

This method allows to get family variants page per page, as a classical pagination.
It's possible to get the total number of family variants with this method.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getFamilyVariantApi()->listPerPage('boots', 50, true);
```

::: warning
There is a maximum limit allowed on server side for the parameter `limit`.
:::

::: warning
Setting the parameter `with_count`  to `true`  can drastically decrease the performance.
It's recommended to let this parameter with the default value `false` if the total number of family variants is not needed in the response.
:::

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the family variants. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$familyVariants = $client->getFamilyVariantApi()->all('boots', 50);
```

:::warning
There is a maximum limit allowed on server side for the parameter `pageSize`.
:::

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Upsert a family variant
::: php-client-availability all-versions

If the family variant does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getFamilyVariantApi()->upsert('boots', [
    'code' => 'rain_boots_color_size',
    'labels' => [
        'de_DE' => 'Stiefel nach Farbe und Größe',
        'en_US' => 'Updating label',
        'fr_FR' => 'Mise à jour du label'
    ]
]);
```

#### Upsert a list of family variants
::: php-client-availability all-versions

This method allows to create or update a list of family variants.
It has the same behavior as the `upsert` method for a single family variant, except that the code must be specified in the data of each family variant.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$responseLines = $client->getFamilyVariantApi()->upsertList('boots', [
    [
        'code' => 'rain_boots_color_size',
        'labels' => [
            'de_DE' => 'Stiefel nach Farbe und Größe',
            'en_US' => 'Updating label',
            'fr_FR' => 'Mise à jour du label'
        ]
    ],
    [
        'code' => 'man_boots_color_size',
        'labels' => [
            'de_DE' => 'Stiefel nach Farbe und Größe',
            'en_US' => 'Updating label',
            'fr_FR' => 'Mise à jour du label'
        ]
    ]
]);

foreach ($responseLines as $line) {
    echo $line['line'];
    echo $line['identifier'];
    echo $line['status_code'];
    if (isset($line['message'])) {
        echo $line['message'];
    }
}
```

::: warning
There is a limit on the maximum number of family variants that you can upsert in one time on server side. By default this limit is set to 100.
:::

You can get a complete description of the expected format and the returned format [here](/api-reference.html#get_families__family_code__variants).
