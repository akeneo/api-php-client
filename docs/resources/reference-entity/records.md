### Reference entity record

#### Get a record of a given reference entity
::: php-client-availability versions=4.0 ee-only

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     'code' => 'kartell',
 *     'values' => [
 *         'labels' => [
 *             [
 *                 'locale' => 'en_US',
 *                 'channel' => null,
 *                 'data' => 'Kartell',
 *             ],
 *         ],
 *         'image' => [
 *             [
 *                 'locale' => null,
 *                 'channel' => null,
 *                 'data' => '0/c/b/0/0cb0c0e115dedba676f8d1ad8343ec207ab54c7b_image.jpg',
 *             ],
 *         ],
 *         'description' => [
 *             [
 *                 'locale' => 'en_US',
 *                 'channel' => null,
 *                 'data' => 'Kartell, the Italian furniture company that sells modern and remarkable pieces of furnitures.',
 *             ],
 *             [
 *                 'locale' => 'fr_FR',
 *                 'channel' => null,
 *                 'data' => 'Kartell, l\'éditeur de meuble italien spécialisé dans la signature de belle pièces au design contemporain.',
 *             ],
 *         ],
 *         'designer' => [
 *             [
 *                 'locale' => null,
 *                 'channel' => null,
 *                 'data' => 'starck',
 *             ],
 *         ],
 *     ],
 * ];
 *
 */
$referenceEntityRecord = $client->getReferenceEntityRecordApi()->get('brand', 'kartell');
```

#### Get the list of the records of a reference entity
::: php-client-availability versions=4.0 ee-only

Records are automatically paginated and can be filtered.

You can get more information about the available query parameters [here](/api-reference.html#get_reference_entity_records).

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$referenceEntityRecordsCursor = $client->getReferenceEntityRecordApi()->all('brand');
```

#### Upsert a record of a given reference entity
::: php-client-availability versions=4.0 ee-only

If the record does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getReferenceEntityRecordApi()->upsert('brand', 'kartell', [
    'code'   => 'kartell',
    'values' => [
        'label'    => [
            [
                'channel' => null,
                'locale'  => 'en_US',
                'data'    => 'Kartell'
            ],
        ],
        'designer' => [
            [
                'locale'  => null,
                'channel' => null,
                'data'    => 'starck',
            ],
        ],
    ]
]);
```

#### Upsert a list of records of a given reference entity 
::: php-client-availability versions=4.0 ee-only

This method allows to create or update a list of records of a given reference entity.
It has the same behavior as the `upsert` method for a single record.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$client->getReferenceEntityRecordApi()->upsertList('brand', [
    [
        'code' => 'kartell',
        'values' => [
            'label' => [
                [
                    'channel' => null,
                    'locale'  => 'fr_FR',
                    'data'    => 'Kartell'
                ],
            ]
        ]
    ],
    [
        'code' => 'lexon',
        'values' => [
            'label' => [
                [
                    'channel' => null,
                    'locale'  => 'en_US',
                    'data'    => 'Lexon'
                ],
            ]
        ]
    ]
]);
```
