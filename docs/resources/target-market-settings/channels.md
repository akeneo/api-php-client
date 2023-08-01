### Channel

#### Get a channel
::: php-client-availability all-versions

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code'             => 'ecommerce',
 *     'currencies'       => ['USD', 'EUR'],
 *     'locales'          => ['de_DE', 'en_US', 'fr_FR'],
 *     'category_tree'    => 'master',
 *     'conversion_units' => [],
 *     'labels'           => [
 *         'en_US' => 'Ecommerce',
 *         'de_DE' => 'Ecommerce',
 *         'fr_FR' => 'Ecommerce',
 *     ],
 * ]
 */
$channel = $client->getChannelApi()->get('ecommerce');
```

#### Get a list of channels
::: php-client-availability all-versions

There are two ways of getting channels. 

**By getting pages**

This method allows to get channels page per page, as a classical pagination.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$firstPage = $client->getChannelApi()->listPerPage(50, true);
```

You can get more information about this method [here](/php-client/list-resources.html#by-getting-pages).

**With a cursor**

This method allows to iterate the channels. It will automatically get the next pages for you.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$channels = $client->getChannelApi()->all(50);
```

You can get more information about this method [here](/php-client/list-resources.html#with-a-cursor).

#### Create a channel
::: php-client-availability all-versions

If the channel does not exist yet, this method creates it, otherwise it throws an exception.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getChannelApi()->create('paper', [
    'category_tree' => 'master',
    'currencies'    => ['EUR', 'USD'],
    'locales'       => ['en_US', 'fr_FR']
    'labels'        => [
        'en_US' => 'Paper',
        'fr_FR' => 'Papier',
    ]
]);
```

#### Upsert a channel
::: php-client-availability all-versions

If the channel does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getChannelApi()->upsert('paper', [
    'category_tree' => 'master',
    'currencies'    => ['EUR', 'USD'],
    'locales'       => ['en_US', 'fr_FR']
    'labels'        => [
        'en_US' => 'Paper',
        'fr_FR' => 'Papier',
    ]
]);
```

#### Upsert a list of channels
::: php-client-availability all-versions

This method allows to create or update a list of channels.
It has the same behavior as the `upsert` method for a single channel, except that the code must be specified in the data of each channel.


```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getChannelApi()->upsertList([
    [
        'code'          => 'paper',
        'category_tree' => 'master',
        'currencies'    => ['EUR', 'USD'],
        'locales'       => ['en_US', 'fr_FR']
        'labels'        => [
            'en_US' => 'Paper',
            'fr_FR' => 'Papier',
        ]
    ],
    [
        'code'             => 'ecommerce',
        'currencies'       => ['USD', 'EUR'],
        'conversion_units' => [],
        'labels'           => [
            'en_US' => 'Ecommerce',
        ]
    ],
]);
```

::: warning
There is a limit on the maximum number of channels that you can upsert in one time on server side. By default this limit is set to 100.
:::
