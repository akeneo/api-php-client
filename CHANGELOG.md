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
