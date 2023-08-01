### Attribute option

#### Get an attribute option
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'       => 'black',
 *     'attribute'  => 'a_simple_select',
 *     'sort_order' => 2,
 *     'labels'     => [
 *         'en_US' => 'Black',
 *         'fr_FR' => 'Noir',
 *     ]
 * ]
 */
$attributeOption = $client->getAttributeOptionApi()->get('a_simple_select', 'black');
```

#### Get a list of attribute options
::: php-client-availability all-versions

There are two ways of getting attribute options. 

**By getting pages**

This method allows to get attribute options page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getAttributeOptionApi()->listPerPage('a_simple_select', 50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the attribute options. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$attributes = $client->getAttributeOptionApi()->all('a_simple_select', 50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create an attribute 
::: php-client-availability all-versions

If the attribute option does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeOptionApi()->create('a_simple_select', 'black', [
    'sort_order' => 2,
    'labels'     => [
        'en_US' => 'Black',
        'fr_FR' => 'Noir',
    ]
]);
```

#### Upsert an attribute option
::: php-client-availability all-versions

If the attribute option does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeOptionApi()->upsert('a_simple_select', 'black', [
    'sort_order' => 2,
    'labels'     => [
        'en_US' => 'Black',
        'fr_FR' => 'Noir',
    ]
]);
```

#### Upsert a list of attribute options
::: php-client-availability versions=2.0

This method allows to create or update a list of attribute options.
It has the same behavior as the `upsert` method for a single attribute option, except that the code and the attribute must be specified in the data of each attribute option.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeOptionApi()->upsertList('a_simple_select',
[
    [
        'code'       => 'black',
        'sort_order' => 2,
        'labels'     => [
            'en_US' => 'Black',
            'fr_FR' => 'Noir',
        ]
    ],
    [
        'code'       => 'white',
        'sort_order' => 3,
        'labels'     => [
            'en_US' => 'White',
            'fr_FR' => 'Blanc',
        ],
    ],
]);
```

::: warning
There is a limit on the maximum number of attribute options that you can upsert in one time on server side. By default this limit is set to 100.
:::
