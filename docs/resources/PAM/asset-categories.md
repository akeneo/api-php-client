::: warning
With the introduction of our brand new way to handle assets, the Asset Manager, the PAM feature will be removed from the v4.0 of the PIM. As a result, **all the methods regarding the PAM assets are now deprecated**.   
To understand why, we recommend you to read this [Medium post](https://medium.com/akeneo-labs/between-stability-and-innovation-c2d2dd61a804), we wrote on this special occasion.  
Also, don't hesitate to take a look at the [Asset Manager documentation](/documentation/asset-manager.html) to discover this new feature and how much more efficient it will be to handle your precious assets.
:::

### Asset category _- Deprecated_

::: warning
This resource is **deprecated**. It means that it may be removed in a future version of the PHP client. To understand why, we recommend you to read this [Medium post](https://medium.com/akeneo-labs/between-stability-and-innovation-c2d2dd61a804), we wrote on this special occasion.  
Also, did you know that since the PIM 3.2 (or the 5.0 of the client), you can handle your assets thanks to the Asset Manager, the brand new efficient way to manage your product assets within the PIM. In the Asset Manager, categories can be modelized thanks to a [single or multiple options attribute](/documentation/asset-manager.html#the-single-and-multiple-options-attributes) in your [asset family](/documentation/asset-manager.html#the-asset-family).  
[Eager to know more about the Asset Manager? It's right here!](/documentation/asset-manager.html#concepts-resources)
:::

#### Get an asset category
::: php-client-availability versions=2.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => 'face',
 *     'parent' => 'images',
 *     'labels' => [
 *         'en_US' => 'Front picture',
 *         'fr_FR' => 'Image de face',
 *     ],
 * ]
 */
$assetCategory = $client->getAssetCategoryApi()->get('face');
```

#### Get a list of asset categories
::: php-client-availability versions=2.0 ee-only

There are two ways of getting asset categories. 

**By getting pages**

This method allows to get asset categories page per page, as a classical pagination.
It's possible to get the total number of asset categories with this method.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getAssetCategoryApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the asset categories. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$assetCategories = $client->getAssetCategoryApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Upsert an asset category
::: php-client-availability versions=2.0 ee-only

If the asset category does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetCategoryApi()->upsert('dos', [
    'parent' => 'images',
    'labels' => [
        'en_US' => 'Back picture',
        'fr_FR' => 'Image de dos',
    ],
]);
```

#### Upsert a list of asset categories
::: php-client-availability versions=2.0 ee-only

This method allows to create or update a list of asset categories.
It has the same behavior as the `upsert` method for a single asset category, except that the code must be specified in the data of each asset category.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetCategoryApi()->upsertList([
    [
        'code'   => 'dos',
        'parent' => 'images',
        'labels' => [
            'en_US' => 'Back picture',
            'fr_FR' => 'Image de dos',
        ],
    ],
    [
        'code'   => 'face',
        'parent' => 'images',
        'labels' => [
            'en_US' => 'Front picture',
            'fr_FR' => 'Image de face',
        ],
    ],
]);
```

::: warning
There is a limit on the maximum number of asset categories that you can upsert in one time on server side. By default this limit is set to 100.
:::
