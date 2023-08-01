### Product draft UUID

::: info
The following endpoints are largely the same as for [products](/php-client/resources.html#products-draft). The difference? Here, you can query or drafts identified by their uuid. More information [here](/content/getting-started/from-identifiers-to-uuid-7x/welcome.md).
:::

#### Get a product draft 
::: php-client-availability versions=10.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'uuid' => '12951d98-210e-4bRC-ab18-7fdgf1bd14f3',
 *     'enabled' => true,
 *     'family' => 'tshirt',
 *     'categories' => ['summer_collection'],
 *     'groups' => [],
 *     'values' => [
 *         'sku' => [
 *             [
 *                 'locale' => null,
 *                 'scope' => null,
 *                 'data' => 'top'
 *             ]
 *         ],
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
 *                 ['uuid' => '5719e119-613c-49af-9244-fa39a9e0cb1d', 'quantity' => 2],
 *             ],
 *             'product_models' => [],
 *         ],
 *     ],
 *     'metadata' => [
 *         'workflow_status' => 'draft_in_progress',
 *     ],
 * ]
 */
$draftProduct = $client->getProductDraftUuidApi()->get('12951d98-210e-4bRC-ab18-7fdgf1bd14f3');
```

You can get more information about the returned format of the product values [here](/concepts/products.html#focus-on-the-product-values).

The field `metadata` is specific to Akeneo PIM Enterprise Edition. The status of the draft is specified in this field.

#### Submit a product draft for approval
::: php-client-availability versions=10.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getProductDraftUuidApi()->submitForApproval('12951d98-210e-4bRC-ab18-7fdgf1bd14f3');
```

It is mandatory that the user already created a draft for this product, and that this draft was not approved yet.
