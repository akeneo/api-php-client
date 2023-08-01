### Published product

#### Get a published product 
::: php-client-availability all-versions ee-only

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
 * ]
 */
$publishedProduct = $client->getPublishedProductApi()->get('top');
```

You can get more information about the returned format of the product values [here](/concepts/products.html#focus-on-the-product-values).

::: warning
The field `product_models` in the `associations` property was added in the 2.1 version of the PIM and is therefore not present in previous versions.
:::

::: warning
The field `quantified_associations` is only available since the 5.0.
:::

#### Get a list of published products
::: php-client-availability all-versions ee-only

There are two ways of getting published products. Like for the products, you can use the [search builder](/php-client/resources.html#search-builder) to ease the construction of a research.

**By getting pages**

This method allows to get published products page per page, as a classical pagination. You can research published products thanks to the search builder.

As for the other entities, it's possible to get the total number of researched published products with this method.
Also, it's possible to filter the value to return, thanks to the query parameters that are fully described [here](/api-reference.html#get_published_products).

For example, in this example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce'])
    ->addFilter('completeness', '<', 85, ['scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();

// set the limit of 50 published products per page, calculate the total number of researched published products, apply a research
$firstPage = $client->getPublishedProductApi()->listPerPage(50, true, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```

::: warning
There is a maximum limit allowed on server side for the parameter `limit`.
:::

::: warning
Setting the parameter `with_count`  to `true`  can drastically decrease the performance. 
It's recommended to let this parameter with the default value `false` if the total number of published products is not needed in the response.
:::

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

You can get more information about the available query parameters [here](/api-reference.html#get_published_products).

**With a cursor**

This method allows to iterate the published products. It will automatically get the next pages for you.
With this method, it's not possible to get the previous page, or getting the total number of published products.

As for the paginated method, the search builder can be used and all query parameters are available, except `with_count`.

For example, in this example, we only return product values belonging to the channel "ecommerce" by adding the query parameter `'scope' => 'ecommerce'`. 

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$searchBuilder = new \Akeneo\Pim\ApiClient\Search\SearchBuilder();
$searchBuilder
    ->addFilter('completeness', '>', 70, ['scope' => 'ecommerce'])
    ->addFilter('completeness', '8', 85, ['scope' => 'ecommerce']);
$searchFilters = $searchBuilder->getFilters();

// get a cursor with a page size of 50, apply a research
$publishedProducts = $client->getPublishedProductApi()->all(50, ['search' => $searchFilters, 'scope' => 'ecommerce']);
```
:::warning
There is a maximum limit allowed on server side for the parameter `pageSize`.
:::

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

You can get more information about the available query parameters [here](/api-reference.html#get_published_products).
