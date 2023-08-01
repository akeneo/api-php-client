# List resources


There are two ways of getting a list of resources, depending of your needs. 
In the following examples, the resource is the product, but the behavior is the same for all entities.


## With a cursor

This method allows to iterate the list of resources thanks to a cursor. It will automatically get the next pages for you.
It could be particularly useful in the context of an export.

With this method, it's not possible to get the previous page, or getting the total number of resources.

You can define the first parameter `page size` to adjust the number of resources returned by page. In this example, the page size is 50.

```php

$products = $client->getProductUuidApi()->all(50);
foreach ($products as $product) {
    // do your stuff here
    echo $product['uuid'];
}
```

:::info
For performance concern, this method is strongly recommended when requesting products, product models or published products.
:::

:::warning
There is a maximum limit allowed on server side for the parameter `pageSize`. By default this limit is set to 100.
:::

## By getting pages

This method allows to get a list of resources page per page, as a classical pagination.


You get the first page by calling the function `listPerpage`. The first parameter `limit` is the number of elements per page.

```php
$firstPage = $client->getProductUuidApi()->listPerPage(50);
```

::: warning
There is a maximum limit allowed on server side for the parameter `limit`.
:::

Then, you can iterate the items of this page:
```php
foreach ($firstPage->getItems() as $product) {
    // do your stuff here
    echo $product['uuid'];
}
```

It's possible to get the total number of resources that will be returned by the pagination, by setting true to the second parameter `with_count`.

```php
$count = $firstPage->getCount();
```

::: warning
Setting the parameter `with_count`  to `true`  can drastically decrease the performance. 
It's recommended to let this parameter with the default value `false` if the total number of resources is not needed in the response.
:::

Also, it's possible to get the next page:

```php
if ($firstPage->hasNextPage()) {
    $secondPage = $firstPage->getNextPage();
}
```

You can get the previous page as well:

```php
if ($secondPage->hasPreviousPage()) {
    $firstPage = $secondPage->getPreviousPage();
}
```
