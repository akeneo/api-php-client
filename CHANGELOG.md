# 5.0.0

## BC Breaks

Use PSR-7, PSR-17 and PSR-18 instead of HttpPlug.

- Change the type of the first parameter of `Akeneo\Pim\ApiClientAkeneoPimClientBuilder::setHttpClient` from `Http\Client\HttpClient` to `Psr\Http\Client\ClientInterface`
- Change the type of the first parameter of `Akeneo\Pim\ApiClientAkeneoPimClientBuilder::setRequestFactory` from `Http\Message\RequestFactory` to `Psr\Http\Message\RequestFactoryInterface`
- Change the type of the first parameter of `Akeneo\Pim\ApiClientAkeneoPimClientBuilder::setStreamFactory` from `Http\Message\StreamFactory` to `Psr\Http\Message\StreamFactoryInterface`

Factory implementations are necessary as dependency.
For example, with Guzzle:

```bash
$ php composer.phar require akeneo/api-php-client php-http/guzzle6-adapter:^2.0 http-interop/http-factory-guzzle:^1.0
```

# 4.0.2 (2019-06-13)

- Add support for PHP 7.1. This is done for some connectors thar are still using it.

Be careful, this PHP version is EOL in december 2020.

# 4.0.0 (2019-02-15)

## BC Breaks

Drop support for PHP 5.6, PHP 7.0 and PHP 7.1

Change the response type from `StreamInterface` to `Response` for `\Akeneo\Pim\ApiClient\Api\MediaFileApiInterface::download`

It allows to get the filename from the response, and also the Mime type.

# 3.0.0 (2018-06-26)

# 2.0.1 (2018-05-03)

## Improvements

- API-592: Handle error when the response is a redirection

# 2.0.0 (2018-02-15)

## Improvements

- API-487: Isolate files manipulation in a dedicated service
- API-562: upsert a list of attribute options
- API-543: Create a new media file and associate it to a product model

## BC Breaks

- Change the constructor of `Akeneo\Pim\ApiClient\Api\ProductMediaFileApi` to add `Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface` 
- Add method `Akeneo\Pim\ApiClient\Api\AttributeOptionApiInterface::upsertList`

# 1.0.x

## Bug Fixes

- API-599: fix const not supported by PHP 5.6

# 1.0.1 (2018-04-05)

## Improvements

- API-592: Handle error when the response is a redirection (https://github.com/akeneo/api-php-client/issues/72)
