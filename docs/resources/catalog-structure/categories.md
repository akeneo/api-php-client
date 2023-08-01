### Category

#### Get a category 
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => 'winter_collection',
 *     'parent' => 'master',
 *     'labels' => [
 *         'en_US' => 'Winter collection',
 *         'fr_FR' => 'Collection hiver',
 *     ]
 * ]
 */
$category = $client->getCategoryApi()->get('master');
```

#### Get a list of categories
::: php-client-availability all-versions

There are two ways of getting categories. 

**By getting pages**

This method allows to get categories page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getCategoryApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the categories. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$categories = $client->getCategoryApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create a category
::: php-client-availability all-versions

If the category does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getCategoryApi()->create('winter_collection', [
    'parent' => 'master',
    'labels' => [
        'en_US' => 'Winter collection',
        'fr_FR' => 'Collection hiver',
    ]
]);
```

#### Upsert a category
::: php-client-availability all-versions

If the category does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getCategoryApi()->upsert('winter_collection', [
    'parent' => 'master',
    'labels' => [
        'en_US' => 'Winter collection',
        'fr_FR' => 'Collection hiver',
    ]
]);
```

#### Upsert a list of categories
::: php-client-availability all-versions

This method allows to create or update a list of categories.
It has the same behavior as the `upsert` method for a single category, except that the code must be specified in the data of each category.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getCategoryApi()->upsertList([
    [
        'code'   => 'winter_collection',
        'parent' => 'master',
        'labels' => [
            'en_US' => 'Winter collection',
            'fr_FR' => 'Collection hiver',
        ]
    ],
    [
        'code'   => 'helicopter',
        'parent' => 'machine',
        'labels' => [
            'en_US' => 'Helicopter',
            'fr_FR' => 'Hélicoptere',
        ]
    ],
]);
```

::: warning
There is a limit on the maximum number of categories that you can upsert in one time on server side. By default this limit is set to 100.
:::
