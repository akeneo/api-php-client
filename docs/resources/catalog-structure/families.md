### Family

#### Get a family 
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'                   => 'caps',
 *     'attributes'             => ['sku', 'name', 'description', 'price', 'color'],
 *     'attribute_as_label'     => 'name',
 *     'attribute_as_image'     => 'picture',
 *     'attribute_requirements' => [
 *         'ecommerce' => ['sku', 'name', 'description', 'price', 'color'],
 *         'tablet'    => ['sku', 'name', 'description', 'price'],
 *     ],
 *     'labels'                 => [
 *         'en_US' => 'Caps',
 *         'fr_FR' => 'Casquettes',
 *     ]
 * ]
 */
$family = $client->getFamilyApi()->get('master');
```

#### Get a list of families 
::: php-client-availability all-versions

There are two ways of getting families. 

**By getting pages**

This method allows to get families page per page, as a classical pagination.
It's possible to get the total number of families with this method.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getFamilyApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the families. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$families = $client->getFamilyApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create a family 
::: php-client-availability all-versions

If the family does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/', 'client_id', 'secret', 'admin', 'admin')->build()

$client->getFamilyApi()->create('caps', [
     'attributes'             => ['sku', 'name', 'description', 'price', 'color'],
     'attribute_as_label'     => 'name',
     'attribute_as_image'     => 'picture',
     'attribute_requirements' => [
         'ecommerce' => ['sku', 'name', 'description', 'price', 'color'],
         'tablet'    => ['sku', 'name', 'description', 'price'],
     ],
     'labels'                 => [
         'en_US' => 'Caps',
         'fr_FR' => 'Casquettes',
     ]
]);
```

#### Upsert a family 
::: php-client-availability all-versions

If the family does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getFamilyApi()->upsert('cap', [
     'attributes'             => ['sku', 'name', 'description', 'price', 'color'],
     'attribute_as_label'     => 'name',
     'attribute_as_image'     => 'picture',
     'attribute_requirements' => [
         'ecommerce' => ['sku', 'name', 'description', 'price', 'color'],
         'tablet'    => ['sku', 'name', 'description', 'price'],
     ],
     'labels'                 => [
         'en_US' => 'Caps',
         'fr_FR' => 'Casquettes',
     ]
]);
```

#### Upsert a list of families 
::: php-client-availability all-versions

This method allows to create or update a list of families.
It has the same behavior as the `upsert` method for a single family, except that the code must be specified in the data of each family.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getFamilyApi()->upsertList([
    [
        'code'                   => 'caps',
        'attributes'             => ['sku', 'name', 'description', 'price', 'color'],
        'attribute_as_label'     => 'name',
        'attribute_as_image'     => 'picture',
        'attribute_requirements' => [
            'ecommerce' => ['sku', 'name', 'description', 'price', 'color'],
            'tablet'    => ['sku', 'name', 'description', 'price'],
        ],
        'labels'                 => [
            'en_US' => 'Caps',
            'fr_FR' => 'Casquettes',
        ]
    ],
    [
        'code'                   => 'hat',
        'attributes'             => ['sku', 'name', 'description', 'price', 'color'],
        'attribute_as_label'     => 'name',
        'attribute_as_image'     => 'picture',
        'attribute_requirements' => [
            'ecommerce' => ['sku', 'name', 'color'],
            'tablet'    => ['sku', 'name'],
        ],
        'labels'                 => [
            'en_US' => 'Hat',
            'fr_FR' => 'Chapeau',
        ]
    ],
]);
```

::: warning
There is a limit on the maximum number of families that you can upsert in one time on server side. By default this limit is set to 100.
:::
