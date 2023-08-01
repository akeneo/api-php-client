### Measurement family

#### Get a list of measurement families
::: php-client-availability versions=6.0

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

/*
 * Returns an array like this:
 * [
 *     [
 *         'code' => 'Angle',
 *         'labels' => [
 *             'en_US' => 'Angle',
 *             'fr_FR' => 'Angle',
 *         ],
 *         'standard_unit_code' => 'RADIAN',
 *         'units' => [
 *             'RADIAN' => [
 *                 'code' => 'RADIAN',
 *                 'labels' => [
 *                     'en_US' => 'Radian',
 *                     'fr_FR' => 'Radian',
 *                 ],
 *                 'convert_from_standard' => [
 *                     [
 *                         'operator' => 'mul',
 *                         'value' => '1',
 *                     ],
 *                 ],
 *                 'symbol' => 'rad',
 *             ],
 *             'MILLIRADIAN' => [
 *                 'code' => 'MILLIRADIAN',
 *                 'labels' => [
 *                     'en_US' => 'Milliradian',
 *                     'fr_FR' => 'Milliradian',
 *                 ],
 *                 'convert_from_standard' => [
 *                     [
 *                         'operator' => 'mul',
 *                         'value' => '0.001',
 *                     ],
 *                 ],
 *                 'symbol' => 'mrad',
 *             ],
 *         ],
 *     ],
 *     ...
 * ]
 */
$measurementFamilies = $client->getMeasurementFamilyApi()->all();
```

You can get a complete description of the returned format [here](/api-reference.html#measurement_families_get_list).

::: warning
There is no pagination on measurement families.
:::

#### Upsert a list of measurement families 
::: php-client-availability versions=6.0

This method allows to create or update a list of measurement families.

For any given measurement family, the `code` must be specified.
If the measurement family does not exist yet, this method creates it, otherwise it updates it.

```php
$client = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://akeneo.com/')->buildAuthenticatedByPassword('client_id', 'secret', 'admin', 'admin');

$responses = $client->getMeasurementFamilyApi()->upsertList([
    // Add a german label to the existing measurement family "Angle"
    [
        'code' => 'Angle',
        'labels' => [
            'de_DE' => 'Winkel',
        ],
    ],
    // Create a new measurement family
    [
        'code' => 'CUSTOM_MEASUREMENT_FAMILY',
        'labels' => [
            'en_US' => 'Custom measurement family',
        ],
        'standard_unit_code' => 'CUSTOM_UNIT',
        'units' => [
            'CUSTOM_UNIT' => [
                'code' => 'CUSTOM_UNIT',
                'labels' => [
                    'en_US' => 'Custom unit',
                ],
                'convert_from_standard' => [
                    [
                        'operator' => 'mul',
                        'value' => '1',
                    ],
                ],
                'symbol' => 'c',
            ],
        ],
    ],
]);

foreach ($responses as $response) {
    echo $response['code'];         // Measurement family code
    echo $response['status_code'];  // 201 => created, 204 => updated, 422 => invalid
}
```

You can get a complete description of the expected format and the returned format [here](/api-reference.html#patch_measurement_families).

::: warning
There is a limit on the maximum number of measurement families you can store. By default this limit is set to 100.
:::
