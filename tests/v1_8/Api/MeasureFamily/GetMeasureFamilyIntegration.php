<?php

namespace Akeneo\Pim\tests\v1_8\Api\MeasureFamily;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetMeasureFamilyIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getMeasureFamilyApi();

        $measureFamily = $api->get('area');

        $this->assertSameContent(
            [
                'code'     => 'area',
                'standard' => 'SQUARE_METER',
                'units'    => [
                    [
                        'code'    => 'SQUARE_MILLIMETER',
                        'convert' => [
                            'mul' => '0.000001',
                        ],
                        'symbol'  => 'mm²',
                    ],
                    [
                        'code'    => 'SQUARE_CENTIMETER',
                        'convert' => [
                            'mul' => '0.0001',
                        ],
                        'symbol'  => 'cm²',
                    ],
                    [
                        'code'    => 'SQUARE_DECIMETER',
                        'convert' => [
                            'mul' => '0.01',
                        ],
                        'symbol'  => 'dm²',
                    ],
                    [
                        'code'    => 'SQUARE_METER',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'm²',
                    ],
                    [
                        'code'    => 'CENTIARE',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'ca',
                    ],
                    [
                        'code'    => 'SQUARE_DEKAMETER',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'dam²',
                    ],
                    [
                        'code'    => 'ARE',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'a',
                    ],
                    [
                        'code'    => 'SQUARE_HECTOMETER',
                        'convert' => [
                            'mul' => '10000',
                        ],
                        'symbol'  => 'hm²',
                    ],
                    [
                        'code'    => 'HECTARE',
                        'convert' => [
                            'mul' => '10000',
                        ],
                        'symbol'  => 'ha',
                    ],
                    [
                        'code'    => 'SQUARE_KILOMETER',
                        'convert' => [
                            'mul' => '1000000',
                        ],
                        'symbol'  => 'km²',
                    ],
                    [
                        'code'    => 'SQUARE_MIL',
                        'convert' => [
                            'mul' => '0.00000000064516',
                        ],
                        'symbol'  => 'sq mil',
                    ],
                    [
                        'code'    => 'SQUARE_INCH',
                        'convert' => [
                            'mul' => '0.00064516',
                        ],
                        'symbol'  => 'in²',
                    ],
                    [
                        'code'    => 'SQUARE_FOOT',
                        'convert' => [
                            'mul' => '0.09290304',
                        ],
                        'symbol'  => 'ft²',
                    ],
                    [
                        'code'    => 'SQUARE_YARD',
                        'convert' => [
                            'mul' => '0.83612736',
                        ],
                        'symbol'  => 'yd²',
                    ],
                    [
                        'code'    => 'ARPENT',
                        'convert' => [
                            'mul' => '3418.89',
                        ],
                        'symbol'  => 'arpent',
                    ],
                    [
                        'code'    => 'ACRE',
                        'convert' => [
                            'mul' => '4046.856422',
                        ],
                        'symbol'  => 'A',
                    ],
                    [
                        'code'    => 'SQUARE_FURLONG',
                        'convert' => [
                            'mul' => '40468.726',
                        ],
                        'symbol'  => 'fur²',
                    ],
                    [
                        'code'    => 'SQUARE_MILE',
                        'convert' => [
                            'mul' => '2589988.110336',
                        ],
                        'symbol'  => 'mi²',
                    ],
                ],
            ],
            $measureFamily
        );
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getMeasureFamilyApi();

        $api->get('unknown');
    }
}
