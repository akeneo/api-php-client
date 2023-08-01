### Asset variation file _- Deprecated_

::: warning
This resource is **deprecated**. It means that it may be removed in a future version of the PHP client. To understand why, we recommend you to read this [Medium post](https://medium.com/akeneo-labs/between-stability-and-innovation-c2d2dd61a804), we wrote on this special occasion.  
Also, did you know that since the PIM 3.2 (or the 5.0 of the client), you can handle your assets thanks to the Asset Manager, the brand new efficient way to manage your product assets within the PIM. In the Asset Manager, asset variation files can be modelized thanks to a [media file attributes](/documentation/asset-manager.html#the-media-file-attribute) in your [asset family](/documentation/asset-manager.html#the-asset-family).  
[Eager to know more about the Asset Manager? It's right here!](/documentation/asset-manager.html#concepts-resources)
:::

#### Get a variation file of a localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => 'f/5/5/c/f55c7ea4adae17d4e02f4d04a839bc2a7cdbf165_chicago_skyline_en_US_mobile.jpg',
 *     'locale' => 'en_US',
 *     'scope'  => 'mobile',
 *     '_link'  => [
 *         'download' => [
 *             'href' => 'http://akeneo.com/api/rest/v1/assets/chicagoskyline/variation-files/mobile/en_US/download',
 *         ],
 *     ],
 * ]
 */
$product = $client->getAssetVariationFileApi()->getFromLocalizableAsset('chicagoskyline', 'mobile', 'en_US');
```

#### Get a variation file of a not localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'   => '0/7/2/1/07217eea32563821b46336d2dec696e4f69415ec_bridge_mobile.jpg',
 *     'locale' => null,
 *     'scope'  => 'mobile',
 *     '_link'  => [
 *         'download' => [
 *             'href' => 'http://akeneo.com/api/rest/v1/assets/bridge/variation-files/mobile/no-locale/download',
 *         ],
 *     ],
 * ]
 */
$product = $client->getAssetVariationFileApi()->getFromNotLocalizableAsset('bridge', 'mobile');
```

#### Download a variation file of a localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$product = $client->getAssetVariationFileApi()->downloadFromLocalizableAsset('chicagoskyline', 'mobile', 'en_US');

file_put_contents('/tmp/chicagoskyline-mobile.jpg', $product->getContents());
```

From the v4 of the PHP client, the response is returned instead of the content. It allows getting the filename and the MIME type from the response.
You can get the content this way:

```php
file_put_contents('/tmp/chicagoskyline-mobile.jpg', $product->getBody()->getContents());
```

#### Download a variation file of a not localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$product = $client->getAssetVariationFileApi()->downloadFromNotLocalizableAsset('bridge', 'mobile');

file_put_contents('/tmp/bridge-mobile.jpg', $product->getContents());
```

From the v4 of the PHP client, the response is returned instead of the content. It allows getting the filename and the MIME type from the response.
You can get the content this way:

```php
file_put_contents('/tmp/bridge-mobile.jpg', $product->getBody()->getContents());
```

#### Upload an asset variation file for a localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetVariationFileApi()->uploadForLocalizableAsset('/tmp/chicagoskyline-mobile.jpg', 'chicagoskyline', 'mobile','en_US');
```

#### Upload an asset variation file for a not localizable asset
::: php-client-availability versions=2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getAssetVariationFileApi()->uploadForNotLocalizableAsset('/tmp/bridge-mobile.jpg', 'bridge', 'mobile');
```

