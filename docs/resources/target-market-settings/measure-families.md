### Measure family

::: warning
Since the 5.0 and for the SaaS versions, we encourage you to use [these new endpoints](#measurement-family), as they are more powerful. They allow you to create/update measurement families and they guarantee the order of the conversion operations.
:::

#### Get a measure family 
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *      'code'     => 'casebox',
 *      'standard' => 'PIECE',
 *      'units' => [
 *          [
 *               'code'    => 'PIECE',
 *               'convert' => [
 *                  'mul' => '1',
 *               ],
 *               'symbol'  => 'Pc',
 *           ],
 *           [
 *               'code'    => 'DOZEN',
 *               'convert' => [
 *                   'mul' => '12',
 *               ],
 *               'symbol'  => 'Dz',
 *           ],
 *      ],
 * ]
 */

$measureFamily = $client->getMeasureFamilyApi()->get('casebox');
```

#### Get a list of measure families
::: php-client-availability all-versions

There are two ways of getting measure families. 

**By getting pages**

This method allows to get measure families page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getMeasureFamilyApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the measure families. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$categories = $client->getMeasureFamilyApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).
