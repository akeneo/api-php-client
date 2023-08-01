### Category media file

#### Download media file 
::: php-client-availability versions=11.2.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$mediaFile = $client->getCategoryMediaFileApi()->download('code/example');

file_put_contents('/tmp/ziggy.jpg', $mediaFile->getBody());
```

####  Get categories with attribute values
::: php-client-availability versions=11.2.0

```php
$firstPage = $client->getCategoryApi()->listPerPage(50, true, ['with_enriched_attributes' => true]);

	foreach ($firstPage->getItems() as $category) {
	    foreach ($category['values'] as $value) {
	        if ($value['type'] === 'image') {
	            $filePath = $value['data']['file_path'];
	        
	            // Download image file for attribute of type image
	            $mediaFile = $client->getCategoryMediaFileApi()->download($filePath);
	            file_put_contents(
	                '/tmp/' . $value['attribute_code'] . $value['data']['extension'],
	                $mediaFile->getBody()
	            );
	        }
	    }
	}
```
