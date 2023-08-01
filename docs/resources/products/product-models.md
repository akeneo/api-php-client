### Product model

#### Get a product model
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');
                     
/*
 * Returns an array like this:
 * [
 *     'code'           => 'rain_boots_red',
 *     'family_variant' => 'boots_color_size',
 *     'parent'         => 'rain_boots',
 *     'categories'     => ['2014_collection', 'winter_collection', 'winter_boots'],
 *     'values'         => [
 *         'name' => [
 *             [
 *                 'locale' => 'en_US',
 *                 'scope' => null,
 *                 'data' => 'Red rain boots',
 *             ]
 *         ],
 *     ],
 *     'associations' => [
 *         'PACK' => [
 *             'products' => [
 *                 'sunglass'
 *             ],
 *             'groups' => [],
 *             'product_models' => []
 *         ],
 *     ],
 *     'quantified_associations' => [
 *         'PRODUCT_SET' => [
 *             'products' => [
 *                 ['identifier' => 'earings', 'quantity' => 2],
 *             ],
 *             'product_models' => [],
 *         ],
 *     ],
 *     'created' => '2017-10-17T14:12:35+00:00',
 *     'updated' => '2017-10-17T14:12:35+00:00'
 * ]
 */
$productModel = $client->getProductModelApi()->get('rain_boots_red');
```

You can get more information about the returned format of the product values [here](/concepts/products.html#focus-on-the-product-values).

Since the 2.3 version, in the Akeneo PIM Enterprise Edition, the response contains one more field `metadata`. Look at the [product model drafts](/php-client/resources.html#product-model-draft) for an example.

#### Get a list of product models
::: php-client-availability all-versions

There are two ways of getting product models.

**Search builder**

::: warning
This feature is only available since the version 3.0 of the PHP API client.
Also it was added in the 2.3 version of the PIM and is therefore not present in previous versions.
:::

You can search over the product models, thanks to a list of filters.
A helper has been added to ease the construction of these filters.

For more information about the available filters and operators that you can use to search a list of product models, please refer to [this page](/documentation/filter.html).

```php
$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', 'AT LEAST COMPLETE', ['locale' => 'en_US'])
    ->addFilter('completeness', 'ALL COMPLETE', ['scope' => 'ecommerce'])
    ->addFilter('categories', 'IN', 'winter_collection');

$searchFilters = $searchBuilder->getFilters();
```

**By getting pages**

This method allows you to get product models page per page, as a classical pagination.
It's possible to get the total number of product models with this method.
As for the paginated method, since the 3.0 version of the PHP client, the search builder can be used and all query parameters are available, except `with_count`.

For example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`. 
```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', 'ALL COMPLETE', ['locale' => 'en_US', 'scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();

// get a cursor with a page size of 50, apply a search
$products = $client->getProductModelApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```

::: warning
There is a maximum limit allowed on server side for the parameter `limit`.
:::

::: warning
Setting the parameter `with_count`  to `true`  can drastically decrease the performance.
It's recommended to let this parameter with the default value `false` if the total number of product models is not needed in the response.
:::

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows you to iterate the product models. It will automatically get the next pages for you.
With this method, it's not possible to get the previous page, or get the total number of product models.

As for the paginated method, since the 3.0 version of the PHP client, the search builder can be used and all query parameters are available, except `with_count`.

For example, in this example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`. 

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', 'ALL COMPLETE', ['locale' => 'en_US']);
$searchFilters = $searchBuilder->getFilters();

// get a cursor with a page size of 50, apply a research
$productModels = $client->getProductModelApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```

:::warning
There is a maximum limit allowed on server side for the parameter `pageSize`.
:::

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

You can get more information about the available query parameters [here](/api-reference.html#get_product_models).

#### Create a product model
::: php-client-availability all-versions

If the product model does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductModelApi()->create('saddle_rain_boots', [
  'family_variant' => 'boots_color_size',
  'parent' => 'rain_boots',
  'categories' => ['2014_collection', 'winter_boots', 'winter_collection'],
  'values' => [
      'name' => [
          [
              'locale' => 'en_US',
              'scope' => null,
              'data' => 'Saddle rain boots',
          ]
      ],
      'color' => [
          [
              'locale' => null,
              'scope' => null,
              'data' => 'saddle'
          ]
      ]
  ]
]);
```

Product model values use the same format as the product values. If you want to know more, take a look at [here](/concepts/products.html#focus-on-the-product-values).

#### Upsert a product model
::: php-client-availability all-versions

If the product model does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductModelApi()->upsert('rain_boots_red', [
    'categories' => ['2014_collection', 'winter_boots']
]);
```

#### Upsert a list of product models
::: php-client-availability all-versions

This method allows to create or update a list of product models.
It has the same behavior as the `upsert` method for a single product model, except that the code must be specified in the data of each product models.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$responseLines = $client->getProductModelApi()->upsertList([
    [
        'code' => 'rain_boots_red',
        'family_variant' => 'rain_boots_color_size',
        'parent' => 'rain_boots',
        'categories' => ['2014_collection', 'winter_boots']
    ],
    [
        'code' => 'rain_boots_saddle',
        'family_variant' => 'rain_boots_color_size',
        'parent' => 'rain_boots',
        'categories' => ['2014_collection', 'winter_boots'],
        'values' => [
            'description' => [
                [
                    'locale' => 'en_US',
                    'scope' => 'ecommerce',
                    'data' => 'Saddle rain boots made of rubber for winter.'
                ]
            ]
        ]
    ]
]);

foreach ($responseLines as $line) {
    echo $line['line'];
    echo $line['identifier'];
    echo $line['status_code'];
    if (isset($line['message'])) {
        echo $line['message'];
    }
}
```

::: warning
There is a limit on the maximum number of product models that you can upsert in one time on server side. By default this limit is set to 100.
:::
