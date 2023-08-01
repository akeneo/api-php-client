### Product

#### Get a product 
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'identifier' => 'top',
 *     'enabled' => true,
 *     'family' => 'tshirt',
 *     'categories' => ['summer_collection'],
 *     'groups' => [],
 *     'parent' => null,
 *     'values' => [
 *         'name' => [
 *              [
 *                  'data' => 'Top',
 *                  'locale' => 'en_US',
 *                  'scope' => null
 *              ],
 *              [
 *                  'data' => 'Débardeur',
 *                  'locale' => 'fr_FR',
 *                  'scope' => null
 *              ],
 *         ],
 *     ],
 *     'created' => '2016-06-23T18:24:44+02:00',
 *     'updated' => '2016-06-25T17:56:12+02:00',
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
 * ]
 */
$product = $client->getProductApi()->get('top');
```

You can get more information about the returned format of the product values [here](/concepts/products.html#focus-on-the-product-values).

In the Akeneo PIM Enterprise Edition, the response contains one more field `metadata`. Look at the [product drafts](/php-client/resources.html#product-draft) for an example.

::: warning
The field `product_models` in the `associations` property was added in the 2.1 version of the PIM and is therefore not present in previous versions.
:::

::: warning
The field `quantified_associations` is only available since the 5.0.
:::

#### Get a list of products
::: php-client-availability all-versions

There are two ways of getting products. Also, you have a search builder to ease the construction of a research.

**Search builder**

You can search over the products, thanks to a list of filters.
An helper has been added to ease the construction of these filters.

For more information about the available filters and operators that you can use to research a list of products, please refer to [this page](/documentation/filter.html).

```php
$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('enabled', '=', true)
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce'])
    ->addFilter('completeness', '<', 85, ['scope' => 'ecommerce'])
    ->addFilter('categories', 'IN', 'winter_collection')
    ->addFilter('family', 'IN', ['camcorders', 'digital_cameras']);

$searchFilters = $searchBuilder->getFilters();
```

**By getting pages**

This method allows to get products page per page, as a classical pagination. You can research products thanks to the search builder.

As for the other entities, it's possible to get the total number of researched products with this method.
Also, it's possible to filter the value to return, thanks to the query parameters that are fully described [here](/api-reference.html#get_products).

For example, in this example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`. 

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce'])
    ->addFilter('completeness', '<', 85, ['scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();

// set the limit of 50 products per page, calculate the total number of researched products, apply a research
$firstPage = $client->getProductApi()->listPerPage(50, true, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```

::: warning
There is a maximum limit allowed on server side for the parameter `limit`.
:::

::: warning
Setting the parameter `with_count`  to `true`  can drastically decrease the performance.  
It's recommended to let this parameter with the default value `false` if the total number of products is not needed in the response.
:::

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

You can get more information about the available query parameters [here](/api-reference.html#get_products).

**With a cursor**

This method allows to iterate the products. It will automatically get the next pages for you.
With this method, it's not possible to get the previous page, or getting the total number of products.

As for the paginated method, the search builder can be used and all query parameters are available, except `with_count`.

For example, in this example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`. 

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce'])
    ->addFilter('completeness', '<', 85, ['scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();

// get a cursor with a page size of 50, apply a research
$products = $client->getProductApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```
:::warning
There is a maximum limit allowed on server side for the parameter `pageSize`.
:::

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

You can get more information about the available query parameters [here](/api-reference.html#get_products).

#### Create a product 
::: php-client-availability all-versions

If the product does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductApi()->create('top', [
    'enabled' => true,
    'family' => 'tshirt',
    'categories' => ['summer_collection'],
    'groups' => [],
    'parent'=> null,
    'values' => [
        'name' => [
            [
                'data' => 'top',
                'locale' => 'en_US',
                'scope' => null,
            ],
            [
                'data' => 'Débardeur',
                'locale' => 'fr_FR',
                'scope' => null,
            ],
        ],
        'price' => [
            [
                'data' => [
                    [
                        'amount' => '15.5',
                        'currency' => 'EUR',
                    ],
                    [
                        'amount' => '15',
                        'currency' => 'USD',
                    ],
                ],
                'locale' => null,
                'scope' => null,
            ],
        ],
    ]
);
```

You can get more information about the expected format of the product values [here](/concepts/products.html#focus-on-the-product-values).

#### Upsert a product 
::: php-client-availability all-versions

If the product does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductApi()->upsert('top', [
    'enabled' => true,
    'family' => 'tshirt',
    'categories' => ['summer_collection'],
    'groups' => [],
    'parent'=> null,
    'values' => [
        'name' => [
            [
                'data' => 'top',
                'locale' => 'en_US',
                'scope' => null,
            ],
            [
                'data' => 'Débardeur',
                'locale' => 'fr_FR',
                'scope' => null,
            ],
        ],
        'price' => [
            [
                'data' => [
                    [
                        'amount' => '15.5',
                        'currency' => 'EUR',
                    ],
                    [
                        'amount' => '15',
                        'currency' => 'USD',
                    ],
                ],
                'locale' => null,
                'scope' => null,
            ],
        ],
    ]
);
```

You can get more information about the expected format of the product values [here](/concepts/products.html#focus-on-the-product-values).

:::warning
If you are using a v2.0 Enterprise Edition PIM, permissions based on your user groups are applied to the product you try to upsert.
If you have edit rights but do not own the product, then it will create a [product draft](/php-client/resources.html#product-draft) instead of updating the product.
:::

#### Upsert a list of products 
::: php-client-availability all-versions

This method allows to create or update a list of products.
It has the same behavior as the `upsert` method for a single product, except that the code must be specified in the data of each product.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$responseLines = $client->getProductApi()->upsertList([
    [
        'identifier' => 'top',
        'family' => 'tshirt',
        'categories' => ['summer_collection'],
        'groups' => [],
        'values' => [
            'price' => [
                [
                    'data' => [
                        [
                            'amount' => '15.5',
                            'currency' => 'EUR',
                        ],
                        [
                            'amount' => '15',
                            'currency' => 'USD',
                        ],
                    ],
                    'locale' => null,
                    'scope' => null,
                ],
            ],
        ],
    ],
    [
        'identifier' => 'cap',
        'categories' => ['hat'],
    ],
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
There is a limit on the maximum number of products that you can upsert in one time on server side. By default this limit is set to 100.
:::

You can get a complete description of the expected format and the returned format [here](/api-reference.html#get_products_uuid__uuid_).

#### Delete a product
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductApi()->delete('top');
```
