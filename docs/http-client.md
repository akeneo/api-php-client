# HTTP client abstraction

We use [HTTPPlug](http://httplug.io/) as the HTTP client abstraction layer. 
Thanks to it, you are free to use the HTTP client implementation and PSR7 implementation that fits your needs.
 
For example, if you already are using Guzzle v5 in an existing project, you can use it with Akeneo PHP client as well. 
Also, it can avoid dependency conflicts that you can experienced by using different version of an HTTP client in the same project (Guzzle v5 and Guzzle v6 for example).

Don't know which client or PSR7 implementation to use? We recommend you to use Guzzle v6:

```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter
```
::: info
Guzle v6 automatically requires its own PSR7 implementation `guzzlehttp/psr7`.
:::

You can get the full list of HTTP adapters [here](https://packagist.org/providers/php-http/client-implementation).

The currently supported HTTP client implementation are:

- php-http/guzzle6-adapter (only with Guzzle PSR7 implementation)
- php-http/curl-client
- php-http/guzzle5-adapter

The currently supported PSR7 implementation are:
- guzzlehttp/psr7
- zendframework/zend-diactoros
- slim/slim

In order to use Guzzle v5 and PSR7 Guzzle implementation, you have to do it:

```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle5-adapter guzzlehttp/psr7
```

If you prefer the native curl client, with Slim PSR7 implementation:

```bash
$ php composer.phar require akeneo/api-php-client php-http/curl-client slim/slim
```

Then, when creating the client, the HTTP client to use will be automatically detected:
 
 ```
$clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://localhost/');
$client = $clientBuilder->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
```
