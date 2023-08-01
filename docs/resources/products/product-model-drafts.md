### Product model draft

#### Get a product model draft 
::: php-client-availability versions=3.0 ee-only

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
 *     'updated' => '2017-10-17T14:12:35+00:00',
 *     'metadata' => [
 *         'workflow_status' => 'draft_in_progress',
 *     ],
 * ]
 */
$draftProduct = $client->getProductModelDraftApi()->get('rain_boots_red');
```

You can get more information about the returned format of the product values [here](/concepts/products.html#focus-on-the-product-values).

The field `metadata` is specific to Akeneo PIM Enterprise Edition. The status of the draft is specified in this field.


#### Submit a product model draft for approval
::: php-client-availability versions=3.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductModelDraftApi()->submitForApproval('rain_boots_red');
```

It is mandatory that the user already created a draft for the product model `rain_boots_red`, and that this draft was not approved yet.
