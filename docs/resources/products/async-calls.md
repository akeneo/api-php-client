### Async Calls

::: php-client-availability versions=11.3

```php
$upsertListResponseFactory = new UpsertResourceListResponseFactory();

$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'username', 'password');

$promise = $client->getProductApi()->upsertAsyncList($productToUpsert);

$response = $upsertListResponseFactory->create($promise->wait()->getBody());
```

All the requests are synchronous by default. But since the version 11.3 of the PHP client, you can now use asynchronous requests on all resources available through the API. For example, to upsert asynchronously a list of resources, you need to use the `upsertAsyncList` method instead of the `upsertList` one. This method returns a promise that you can wait for to get the response.
