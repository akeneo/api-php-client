### Product media file

#### Get media file information
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'              => 'code/example',
 *     'original_filename' => 'ziggy.jpg',
 *     'mime_type'         => 'image/jpeg',
 *     'size'              => 1337,
 *     'extension'         => 'jpg',
 *     '_links'            => [
 *         'download' => [
 *             'href' => 'http://localhost/api/rest/v1/media-files/code/example/download',
 *         ],
 *     ],
 * ];
 */
$media = $client->getProductMediaFileApi()->get('code/example');
```

#### Download media file 
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$mediaFile = $client->getProductMediaFileApi()->download('code/example');

file_put_contents('/tmp/ziggy.jpg', $mediaFile->getContents());
```

From the v4 of the PHP client, the response is returned instead of the content. It allows getting the filename and the MIME type from the response.
You can get the content this way:

```php
file_put_contents('/tmp/bridge.jpg', $mediaFile->getBody()->getContents());
```

#### Get a list of media file information
::: php-client-availability all-versions

There are two ways of getting media files.

**By getting pages**

This method allows to get media files page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getProductMediaFileApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the media files. It will automatically get the next pages for you.
With this method, it's not possible to get the previous page, or getting the total number of media files.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

// get a cursor with a page size of 50, apply a research
$mediaFiles = $client->getProductMediaFileApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create a new media file 
::: php-client-availability all-versions

When you create a media file, you can directly associate it to either a product or a product model.

**Association to a product**

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductMediaFileApi()->create('/tmp/ziggy.jpg', [
    'identifier' => 'medium_boot',
    'attribute'  => 'side_view',
    'scope'      => 'ecommerce',
    'locale'     => 'en_US',
]);
```

**Association to a product model**

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductMediaFileApi()->create('/tmp/ziggy.jpg', [
    'code'       => 'rain_boots',
    'attribute'  => 'product_model_media',
    'scope'      => null,
    'locale'     => 'en_US',
    'type'       => 'product_model',
]);
```
