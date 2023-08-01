### Attribute group

#### Get an attribute group
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'       => 'marketing',
 *     'attributes' => ['sku', 'name', 'description', 'response_time', 'release_date', 'price'],
 *     'sort_order' => 4,
 *     'labels'     => [
 *          'en_US' => 'Marketing',
 *          'fr_FR' => 'Marketing',
 *      ],
 * ]
 */
$attributeGroup = $client->getAttributeGroupApi()->get('marketing');
```

#### Get a list of attribute groups
::: php-client-availability all-versions

There are two ways of getting attribute groups.
 
**By getting pages**
 
 This method allows to get attribute groups page per page, as a classical pagination.
 
```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getAttributeGroupApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the attribute groups. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$attributeGroups = $client->getAttributeGroupApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create an attribute group
::: php-client-availability all-versions

If the attribute group does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeGroupApi()->create('media', [
    'attribute' => ['side_view'],
    'labels' => [
        'en_US' => 'Media',
        'fr_FR' => 'Médias',
    ]
]);
```

#### Upsert an attribute group
::: php-client-availability all-versions

If the attribute group does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeGroupApi()->upsert('marketing', [
    'attributes' => ['sku', 'name', 'description'],
    'labels' => [
        'en_US' => 'Marketing',
        'fr_FR' => 'Marketing',
    ]
]);
```

#### Upsert a list of attribute groups
::: php-client-availability all-versions

This method allows to create or update a list of attribute groups.
It has the same behavior as the `upsert` method for a single attribute group, except that the code must be specified in the data of each attribute group.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAttributeGroupApi()->upsertList([
    [
        'code'   => 'marketing',
        'attributes' => ['sku', 'name', 'description'],
        'labels' => [
            'en_US' => 'Marketing',
            'fr_FR' => 'Marketing',
        ]
    ],
    [
        'code'   => 'media',
        'attribute' => ['side_view'],
            'labels' => [
                'en_US' => 'Media',
                'fr_FR' => 'Médias',
            ]
    ],
]);
```

::: warning
There is a limit on the maximum number of attribute groups that you can upsert in one time on server side. By default this limit is set to 100.
:::
