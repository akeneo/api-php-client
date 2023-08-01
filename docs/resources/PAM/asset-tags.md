### Asset tag _- Deprecated_

::: warning
This resource is **deprecated**. It means that it may be removed in a future version of the PHP client. To understand why, we recommend you to read this [Medium post](https://medium.com/akeneo-labs/between-stability-and-innovation-c2d2dd61a804), we wrote on this special occasion.  
Also, did you know that since the PIM 3.2 (or the 5.0 of the client), you can handle your assets thanks to the Asset Manager, the brand new efficient way to manage your product assets within the PIM. In the Asset Manager, tags can be modelized thanks to a [single or multiple options attribute](/documentation/asset-manager.html#the-single-and-multiple-options-attributes) in your [asset family](/documentation/asset-manager.html#the-asset-family).  
[Eager to know more about the Asset Manager? It's right here!](/documentation/asset-manager.html#concepts-resources)
:::

#### Get an asset tag
::: php-client-availability versions=2.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code' => 'water',
 * ]
 */
$assetTag = $client->getAssetTagApi()->get('water');
```

#### Get a list of asset tags
::: php-client-availability versions=2.0 ee-only

There are two ways of getting asset tags.

**By getting pages**

This method allows to get asset tags page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getAssetTagApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the asset tags. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$assetTags = $client->getAssetTagApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Upsert an asset tag
::: php-client-availability versions=2.0 ee-only

If the asset tag does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetTagApi()->upsert('cat');
```
