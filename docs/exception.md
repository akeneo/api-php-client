# Exception handling

Every request made with the client can throw an HTTP exception.

You can read more about REST API Exception handling : [here](https://api.akeneo.com/documentation/responses.html#)

## HTTP exception

The parent of these HTTP exceptions is `Akeneo\Pim\Exception\HttpException`.
You can get the request and the response that are responsible for this exception. Also, the HTTP response code and response message are available.
 
```php
try {
    $client->getProductUuidApi()->get('1cf1d135-26fe-4ac2-9cf5-cdb69ada0547');
} catch (HttpException $e) {
    // do your stuff with the exception
    $requestBody = $e->getRequest()->getBody();
    $responseBody = $e->getResponse()->getBody();
    $httpCode = $e->getCode(); 
    $errorMessage = $e->getMessage(); 
}
```

 
Two types of exception inherit from this exception: server exception and client exception.

## Server exception (5XX)

A server exception thrown by the client means that the server failed to fulfill an apparently valid request.
It's an HTTP code from the 5xx family.

## Client exception (4XX)

A client exception could be thrown if the client made an invalid request.
It's an HTTP code from the 4xx family.

### Bad request exception (400)

This exception is thrown if the request does not contain valid JSON. It corresponds to the HTTP code 400.

::: info
It should not occur with the PHP client, because the JSON is generated from PHP array.
:::

### Unauthorized exception (401)

This exception is thrown if you don't have the permission to access to the resource. It corresponds to the HTTP code 401.

::: info
It should not occur as the PHP client automatically authenticates the request for you.
:::

### Forbidden exception (403)

This exception is thrown if the server understands the request but refuses to authorize it.
It corresponds to the HTTP code 403.

### Not found exception (404)

This exception is thrown if the requested resource does not exist. It corresponds to the HTTP code 404.

### Method not allowed exception (405)

This exception is thrown if the requested resource doesn't support this method. It corresponds to the HTTP code 405.

### Not acceptable exception (406)

This exception is thrown if the server cannot produce a response matching the list of acceptable values defined in the
request's proactive content negotiation headers, and that the server is unwilling to supply a default representation.
It corresponds to the HTTP code 406.

### Unsupported media type exception (415)

This exception is thrown if the payload format is in an unsupported format. It corresponds to the HTTP code 415.

### Unprocessable entity exception (422)

This exception is thrown if the data are not valid. In this exception, an array of errors could be returned.
It returns an empty array if there is no error in the array.

```php
try {
    $client->getProductUuidApi()->create('1cf1d135-26fe-4ac2-9cf5-cdb69ada0547');
} catch (UnprocessableEntityHttpException $e) {
    // do your stuff with the exception
    $requestBody = $e->getRequest()->getBody();
    $responseBody = $e->getResponse()->getBody();
    $httpCode = $e->getCode(); 
    $errorMessage = $e->getMessage(); 
    $errors = $e->getResponseErrors();
    foreach ($e->getResponseErrors() as $error) {
        // do your stuff with the error
        echo $error['property'];
        echo $error['message'];
    }
}
```

### Too many requests exception (429)

This exception is thrown if too many requests are sent in an amount of time ("rate limiting").
It corresponds to the HTTP code 429.

```php
try {
    $client->getProductUuidApi()->get('1cf1d135-26fe-4ac2-9cf5-cdb69ada0547');
} catch (TooManyRequestsHttpException $e) {
    $requestBody = $e->getRequest()->getBody();
    $responseBody = $e->getResponse()->getBody();
    $httpCode = $e->getCode();
    $errorMessage = $e->getMessage();
    $retryAfter = $e->getRetryAfter();
}
```