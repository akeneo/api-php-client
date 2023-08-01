### Association type

#### Get an association type
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'       => 'X_SELL',
 *     'labels'     => [
 *          'en_US' => 'Cross sell',
 *          'fr_FR' => 'Vente croisée',
 *      ],
 *      'is_quantified' => false,
 *      'is_two_way' => false,
 * ]
 */
$associationType = $client->getAssociationTypeApi()->get('X_SELL');
```

#### Get a list of association types
::: php-client-availability all-versions

There are two ways of getting association types.
 
**By getting pages**
 
 This method allows to get association types page per page, as a classical pagination.
 
```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getAssociationTypeApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the association types. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$associationTypes = $client->getAssociationTypeApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create an association type
::: php-client-availability all-versions

If the association type does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssociationTypeApi()->create('NEW_SELL', [
    'labels' => [
        'en_US' => 'New sell',
        'fr_FR' => 'Nouvelle vente',
    ]
]);
```

#### Upsert an association type
::: php-client-availability all-versions

If the association type does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssociationTypeApi()->upsert('NEW_SELL', [
    'labels' => [
        'en_US' => 'New sell',
        'fr_FR' => 'Nouvelle vente',
    ]
]);
```

#### Upsert a list of association types
::: php-client-availability all-versions

This method allows to create or update a list of association types.
It has the same behavior as the `upsert` method for a single association type, except that the code must be specified in the data of each association type.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssociationTypeApi()->upsertList([
    [
        'code'   => 'NEW_SELL',
        'labels' => [
            'en_US' => 'New sell',
            'fr_FR' => 'Nouvelle vente',
        ]
    ],
    [
        'code'   => 'UPSELL',
        'labels' => [
            'en_US' => 'Upsell',
            'fr_FR' => 'Vente incitative',
        ]
    ],
]);
```

::: warning
There is a limit on the maximum number of association types that you can upsert in one time on server side. By default this limit is set to 100.
:::
