# 2.0.x

## Bug fixes

- API-592: Handle error when the response is a redirection

# 2.0.0 (2018-02-15)

## Improvements

- API-487: Isolate files manipulation in a dedicated service
- API-562: upsert a list of attribute options
- API-543: Create a new media file and associate it to a product model

## BC Breaks

- Change the constructor of `Akeneo\Pim\ApiClient\Api\ProductMediaFileApi` to add `Akeneo\Pim\ApiClient\FileSystem\FileSystemInterface` 
- Add method `Akeneo\Pim\ApiClient\Api\AttributeOptionApiInterface::upsertList`
