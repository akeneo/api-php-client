<?php

namespace Akeneo\Pim\tests\v2_0\Api\MeasureFamily;

use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class ListMeasureFamilyIntegration extends ApiTestCase
{
    public function testListPerPage()
    {
        $api = $this->createClient()->getMeasureFamilyApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];
        $expectedMeasureFamilies = $this->getExpectedMeasureFamilies();

        $firstPage = $api->listPerPage(5);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertNull($firstPage->getCount());
        $this->assertNull($firstPage->getPreviousLink());
        $this->assertNull($firstPage->getPreviousPage());
        $this->assertFalse($firstPage->hasPreviousPage());
        $this->assertTrue($firstPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=2&limit=5&with_count=false', $firstPage->getNextLink());

        $measureFamilies = $firstPage->getItems();
        $this->assertCount(5 ,$measureFamilies);
        for ($i = 0; $i < 5; $i++) {
            $this->assertSameContent($expectedMeasureFamilies[$i], $measureFamilies[$i]);
        }

        $secondPage = $firstPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $secondPage);
        $this->assertTrue($secondPage->hasPreviousPage());
        $this->assertTrue($secondPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=1&limit=5&with_count=false', $secondPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=3&limit=5&with_count=false', $secondPage->getNextLink());

        $measureFamilies = $secondPage->getItems();
        $this->assertCount(5 ,$measureFamilies);
        for ($i = 0; $i < 5; $i++) {
            $this->assertSameContent($expectedMeasureFamilies[5 + $i], $measureFamilies[$i]);
        }

        $thirdPage = $secondPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $thirdPage);
        $this->assertTrue($thirdPage->hasPreviousPage());
        $this->assertTrue($thirdPage->hasNextPage());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=2&limit=5&with_count=false', $thirdPage->getPreviousLink());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=4&limit=5&with_count=false', $thirdPage->getNextLink());

        $measureFamilies = $thirdPage->getItems();
        $this->assertCount(5 ,$measureFamilies);
        for ($i = 0; $i < 5; $i++) {
            $this->assertSameContent($expectedMeasureFamilies[10 + $i], $measureFamilies[$i]);
        }

        $lastPage = $thirdPage->getNextPage();
        $this->assertInstanceOf(PageInterface::class, $lastPage);
        $this->assertTrue($lastPage->hasPreviousPage());
        $this->assertFalse($lastPage->hasNextPage());
        $this->assertNull($lastPage->getNextPage());
        $this->assertNull($lastPage->getNextLink());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=3&limit=5&with_count=false', $lastPage->getPreviousLink());

        $measureFamilies = $lastPage->getItems();
        $this->assertCount(3 ,$measureFamilies);
        for ($i = 0; $i < 3; $i++) {
            $this->assertSameContent($expectedMeasureFamilies[15 + $i], $measureFamilies[$i]);
        }

        $previousPage = $lastPage->getPreviousPage();
        $this->assertInstanceOf(PageInterface::class, $previousPage);
        $this->assertSame($thirdPage->getItems(), $previousPage->getItems());
    }

    public function testListPerPageWithCount()
    {
        $api = $this->createClient()->getMeasureFamilyApi();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(10, true);
        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame(18, $firstPage->getCount());
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=2&limit=10&with_count=true', $firstPage->getNextLink());
    }

    public function testListPerPageWithSpecificQueryParameter()
    {
        $api = $this->createClient()->getMeasureFamilyApi();
        $expectedMeasureFamilies = $this->getExpectedMeasureFamilies();
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        $firstPage = $api->listPerPage(1, false, ['foo' => 'bar']);

        $this->assertInstanceOf(PageInterface::class, $firstPage);
        $this->assertSame($baseUri . '/api/rest/v1/measure-families?page=2&limit=1&with_count=false&foo=bar', $firstPage->getNextLink());

        $measureFamilies = $firstPage->getItems();
        $this->assertCount(1 ,$measureFamilies);
        $this->assertSameContent($expectedMeasureFamilies[0], $measureFamilies[0]);
    }

    public function testAll()
    {
        $api = $this->createClient()->getMeasureFamilyApi();
        $measureFamilies = $api->all();

        $this->assertInstanceOf(ResourceCursorInterface::class, $measureFamilies);

        $measureFamilies = iterator_to_array($measureFamilies);

        $this->assertCount(18, $measureFamilies);
        $this->assertSameContent($this->getExpectedMeasureFamilies(), $measureFamilies);
    }

    public function testAllWithUselessQueryParameter()
    {
        $api = $this->createClient()->getMeasureFamilyApi();
        $measureFamilies = $api->all(10, ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceCursorInterface::class, $measureFamilies);

        $measureFamilies = iterator_to_array($measureFamilies);

        $this->assertCount(18, $measureFamilies);
        $this->assertSameContent($this->getExpectedMeasureFamilies(), $measureFamilies);
    }

    public function getExpectedMeasureFamilies()
    {
        $baseUri = $this->getConfiguration()['api']['baseUri'];

        return [
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/area',
                    ],
                ],
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
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/binary',
                    ],
                ],
                'code'     => 'binary',
                'standard' => 'BYTE',
                'units'    => [
                    [
                        'code'    => 'BIT',
                        'convert' => [
                            'mul' => '0.125',
                        ],
                        'symbol'  => 'b',
                    ],
                    [
                        'code'    => 'BYTE',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'B',
                    ],
                    [
                        'code'    => 'KILOBYTE',
                        'convert' => [
                            'mul' => '1024',
                        ],
                        'symbol'  => 'kB',
                    ],
                    [
                        'code'    => 'MEGABYTE',
                        'convert' => [
                            'mul' => '1048576',
                        ],
                        'symbol'  => 'MB',
                    ],
                    [
                        'code'    => 'GIGABYTE',
                        'convert' => [
                            'mul' => '1073741824',
                        ],
                        'symbol'  => 'GB',
                    ],
                    [
                        'code'    => 'TERABYTE',
                        'convert' => [
                            'mul' => '1099511627776',
                        ],
                        'symbol'  => 'TB',
                    ],
                ]
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/decibel',
                    ],
                ],
                'code'     => 'decibel',
                'standard' => 'DECIBEL',
                'units'    => [
                    [
                        'code'    => 'DECIBEL',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'dB',
                    ],
                ],
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/frequency',
                    ],
                ],
                'code'     => 'frequency',
                'standard' => 'HERTZ',
                'units'    => [
                    [
                        'code'    => 'HERTZ',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'Hz',
                    ],
                    [
                        'code'    => 'KILOHERTZ',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kHz',
                    ],
                    [
                        'code'    => 'MEGAHERTZ',
                        'convert' => [
                            'mul' => '1000000',
                        ],
                        'symbol'  => 'MHz',
                    ],
                    [
                        'code'    => 'GIGAHERTZ',
                        'convert' => [
                            'mul' => '1000000000',
                        ],
                        'symbol'  => 'GHz',
                    ],
                    [
                        'code'    => 'TERAHERTZ',
                        'convert' => [
                            'mul' => '1000000000000',
                        ],
                        'symbol'  => 'THz',
                    ],
                ],
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/length',
                    ],
                ],
                'code'     => 'length',
                'standard' => 'METER',
                'units'    => [
                    [
                        'code'    => 'MILLIMETER',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mm',
                    ],
                    [
                        'code'    => 'CENTIMETER',
                        'convert' => [
                            'mul' => '0.01',
                        ],
                        'symbol'  => 'cm',
                    ],
                    [
                        'code'    => 'DECIMETER',
                        'convert' => [
                            'mul' => '0.1',
                        ],
                        'symbol'  => 'dm',
                    ],
                    [
                        'code'    => 'METER',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'm',
                    ],
                    [
                        'code'    => 'DEKAMETER',
                        'convert' => [
                            'mul' => '10',
                        ],
                        'symbol'  => 'dam',
                    ],
                    [
                        'code'    => 'HECTOMETER',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'hm',
                    ],
                    [
                        'code'    => 'KILOMETER',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'km',
                    ],
                    [
                        'code'    => 'MIL',
                        'convert' => [
                            'mul' => '0.0000254',
                        ],
                        'symbol'  => 'mil',
                    ],
                    [
                        'code'    => 'INCH',
                        'convert' => [
                            'mul' => '0.0254',
                        ],
                        'symbol'  => 'in',
                    ],
                    [
                        'code'    => 'FEET',
                        'convert' => [
                            'mul' => '0.3048',
                        ],
                        'symbol'  => 'ft',
                    ],
                    [
                        'code'    => 'YARD',
                        'convert' => [
                            'mul' => '0.9144',
                        ],
                        'symbol'  => 'yd',
                    ],
                    [
                        'code'    => 'CHAIN',
                        'convert' => [
                            'mul' => '20.1168',
                        ],
                        'symbol'  => 'ch',
                    ],
                    [
                        'code'    => 'FURLONG',
                        'convert' => [
                            'mul' => '201.168',
                        ],
                        'symbol'  => 'fur',
                    ],
                    [
                        'code'    => 'MILE',
                        'convert' => [
                            'mul' => '1609.344',
                        ],
                        'symbol'  => 'mi',
                    ],
                ],
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/power',
                    ],
                ],
                'code'     => 'power',
                'standard' => 'WATT',
                'units'    => [
                    [
                        'code'    => 'WATT',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'W',
                    ],
                    [
                        'code'    => 'KILOWATT',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kW',
                    ],
                    [
                        'code'    => 'MEGAWATT',
                        'convert' => [
                            'mul' => '1000000',
                        ],
                        'symbol'  => 'MW',
                    ],
                    [
                        'code'    => 'GIGAWATT',
                        'convert' => [
                            'mul' => '1000000000',
                        ],
                        'symbol'  => 'GW',
                    ],
                    [
                        'code'    => 'TERAWATT',
                        'convert' => [
                            'mul' => '1000000000000',
                        ],
                        'symbol'  => 'TW',
                    ],
                ],
            ],
            [
                '_links'           => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/voltage',
                    ],
                ],
                'code'     => 'voltage',
                'standard' => 'VOLT',
                'units'    => [
                    [
                        'code'    => 'MILLIVOLT',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mV',
                    ],
                    [
                        'code'    => 'CENTIVOLT',
                        'convert' => [
                            'mul' => '0.01',
                        ],
                        'symbol'  => 'cV',
                    ],
                    [
                        'code'    => 'DECIVOLT',
                        'convert' => [
                            'mul' => '0.1',
                        ],
                        'symbol'  => 'dV',
                    ],
                    [
                        'code'    => 'VOLT',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'V',
                    ],
                    [
                        'code'    => 'DEKAVOLT',
                        'convert' => [
                            'mul' => '10',
                        ],
                        'symbol'  => 'daV',
                    ],
                    [
                        'code'    => 'HECTOVOLT',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'hV',
                    ],
                    [
                        'code'    => 'KILOVOLT',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kV',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/intensity',
                    ],
                ],
                'code'     => 'intensity',
                'standard' => 'AMPERE',
                'units' => [
                    [
                        'code'    => 'MILLIAMPERE',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mA',
                    ],
                    [
                        'code'    => 'CENTIAMPERE',
                        'convert' => [
                            'mul' => '0.01',
                        ],
                        'symbol'  => 'cA',
                    ],
                    [
                        'code'    => 'DECIAMPERE',
                        'convert' => [
                            'mul' => '0.1',
                        ],
                        'symbol'  => 'dA',
                    ],
                    [
                        'code'    => 'AMPERE',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'A',
                    ],
                    [
                        'code'    => 'DEKAMPERE',
                        'convert' => [
                            'mul' => '10',
                        ],
                        'symbol'  => 'daA',
                    ],
                    [
                        'code'    => 'HECTOAMPERE',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'hA',
                    ],
                    [
                        'code'    => 'KILOAMPERE',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kA',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/resistance',
                    ],
                ],
                'code'     => 'resistance',
                'standard' => 'OHM',
                'units' => [
                    [
                        'code'    => 'MILLIOHM',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mΩ',
                    ],
                    [
                        'code'    => 'CENTIOHM',
                        'convert' => [
                            'mul' => '0.01',
                        ],
                        'symbol'  => 'cΩ',
                    ],
                    [
                        'code'    => 'DECIOHM',
                        'convert' => [
                            'mul' => '0.1',
                        ],
                        'symbol'  => 'dΩ',
                    ],
                    [
                        'code'    => 'OHM',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'Ω',
                    ],
                    [
                        'code'    => 'DEKAOHM',
                        'convert' => [
                            'mul' => '10',
                        ],
                        'symbol'  => 'daΩ',
                    ],
                    [
                        'code'    => 'HECTOHM',
                        'convert' => [
                            'mul' => '100',
                        ],
                        'symbol'  => 'hΩ',
                    ],
                    [
                        'code'    => 'KILOHM',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kΩ',
                    ],
                    [
                        'code'    => 'MEGOHM',
                        'convert' => [
                            'mul' => '1000000',
                        ],
                        'symbol'  => 'MΩ',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/speed',
                    ],
                ],
                'code'     => 'speed',
                'standard' => 'METER_PER_SECOND',
                'units' => [
                    [
                        'code'    => 'METER_PER_SECOND',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'mdivs',
                    ],
                    [
                        'code'    => 'METER_PER_MINUTE',
                        'convert' => [
                            'div' => '60',
                        ],
                        'symbol'  => 'mdivm',
                    ],
                    [
                        'code'    => 'METER_PER_HOUR',
                        'convert' => [
                            'mul' => '1',
                            'div' => '3600',
                        ],
                        'symbol'  => 'mdivh',
                    ],
                    [
                        'code'    => 'KILOMETER_PER_HOUR',
                        'convert' => [
                            'mul' => '1000',
                            'div' => '3600',
                        ],
                        'symbol'  => 'kmdivh',
                    ],
                    [
                        'code'    => 'FOOT_PER_SECOND',
                        'convert' => [
                            'mul' => '0.3048',
                        ],
                        'symbol'  => 'ftdivs',
                    ],
                    [
                        'code'    => 'FOOT_PER_HOUR',
                        'convert' => [
                            'mul' => '0.3048',
                            'div' => '3600',
                        ],
                        'symbol'  => 'ftdivh',
                    ],
                    [
                        'code'    => 'YARD_PER_HOUR',
                        'convert' => [
                            'mul' => '0.9144',
                            'div' => '3600',
                        ],
                        'symbol'  => 'yddivh',
                    ],
                    [
                        'code'    => 'MILE_PER_HOUR',
                        'convert' => [
                            'mul' => '1609.344',
                            'div' => '3600',
                        ],
                        'symbol'  => 'midivh',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/electriccharge',
                    ],
                ],
                'code'     => 'electriccharge',
                'standard' => 'AMPEREHOUR',
                'units' => [
                    [
                        'code'    => 'MILLIAMPEREHOUR',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mAh',
                    ],
                    [
                        'code'    => 'AMPEREHOUR',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'Ah',
                    ],
                    [
                        'code'    => 'MILLICOULOMB',
                        'convert' => [
                            'div' => '3600000',
                        ],
                        'symbol'  => 'mC',
                    ],
                    [
                        'code'    => 'CENTICOULOMB',
                        'convert' => [
                            'div' => '360000',
                        ],
                        'symbol'  => 'cC',
                    ],
                    [
                        'code'    => 'DECICOULOMB',
                        'convert' => [
                            'div' => '36000',
                        ],
                        'symbol'  => 'dC',
                    ],
                    [
                        'code'    => 'COULOMB',
                        'convert' => [
                            'div' => '3600',
                        ],
                        'symbol'  => 'C',
                    ],
                    [
                        'code'    => 'DEKACOULOMB',
                        'convert' => [
                            'div' => '360',
                        ],
                        'symbol'  => 'daC',
                    ],
                    [
                        'code'    => 'HECTOCOULOMB',
                        'convert' => [
                            'div' => '36',
                        ],
                        'symbol'  => 'hC',
                    ],
                    [
                        'code'    => 'KILOCOULOMB',
                        'convert' => [
                            'div' => '3.6',
                        ],
                        'symbol'  => 'kC',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/duration',
                    ],
                ],
                'code'     => 'duration',
                'standard' => 'SECOND',
                'units' => [
                    [
                        'code'    => 'MILLISECOND',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'ms',
                    ],
                    [
                        'code'    => 'SECOND',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 's',
                    ],
                    [
                        'code'    => 'MINUTE',
                        'convert' => [
                            'mul' => '60',
                        ],
                        'symbol'  => 'm',
                    ],
                    [
                        'code'    => 'HOUR',
                        'convert' => [
                            'mul' => '3600',
                        ],
                        'symbol'  => 'h',
                    ],
                    [
                        'code'    => 'DAY',
                        'convert' => [
                            'mul' => '86400',
                        ],
                        'symbol'  => 'd',
                    ],
                    [
                        'code'    => 'WEEK',
                        'convert' => [
                            'mul' => '604800',
                        ],
                        'symbol'  => 'week',
                    ],
                    [
                        'code'    => 'MONTH',
                        'convert' => [
                            'mul' => '18748800',
                        ],
                        'symbol'  => 'month',
                    ],
                    [
                        'code'    => 'YEAR',
                        'convert' => [
                            'mul' => '31536000',
                        ],
                        'symbol'  => 'year',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/temperature',
                    ],
                ],
                'code'     => 'temperature',
                'standard' => 'KELVIN',
                'units' => [
                    [
                        'code'    => 'CELSIUS',
                        'convert' => [
                            'add' => '273.15',
                        ],
                        'symbol'  => '°C',
                    ],
                    [
                        'code'    => 'FAHRENHEIT',
                        'convert' => [
                            'sub' => '32',
                            'div' => '1.8',
                            'add' => '273.15',
                        ],
                        'symbol'  => '°F',
                    ],
                    [
                        'code'    => 'KELVIN',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => '°K',
                    ],
                    [
                        'code'    => 'RANKINE',
                        'convert' => [
                            'div' => '1.8',
                        ],
                        'symbol'  => '°R',
                    ],
                    [
                        'code'    => 'REAUMUR',
                        'convert' => [
                            'mul' => '1.25',
                            'add' => '273.15',
                        ],
                        'symbol'  => '°r',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/volume',
                    ],
                ],
                'code'     => 'volume',
                'standard' => 'CUBIC_METER',
                'units' => [
                    [
                        'code'    => 'CUBIC_MILLIMETER',
                        'convert' => [
                            'mul' => '0.000000001',
                        ],
                        'symbol'  => 'mm³',
                    ],
                    [
                        'code'    => 'CUBIC_CENTIMETER',
                        'convert' => [
                            'mul' => '0.000001',
                        ],
                        'symbol'  => 'cm³',
                    ],
                    [
                        'code'    => 'MILLILITER',
                        'convert' => [
                            'mul' => '0.000001',
                        ],
                        'symbol'  => 'ml',
                    ],
                    [
                        'code'    => 'CENTILITER',
                        'convert' => [
                            'mul' => '0.00001',
                        ],
                        'symbol'  => 'cl',
                    ],
                    [
                        'code'    => 'DECILITER',
                        'convert' => [
                            'mul' => '0.0001',
                        ],
                        'symbol'  => 'dl',
                    ],
                    [
                        'code'    => 'CUBIC_DECIMETER',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'dm³',
                    ],
                    [
                        'code'    => 'LITER',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'l',
                    ],
                    [
                        'code'    => 'CUBIC_METER',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'm³',
                    ],
                    [
                        'code'    => 'OUNCE',
                        'convert' => [
                            'mul' => '0.00454609',
                            'div' => '160',
                        ],
                        'symbol'  => 'oz',
                    ],
                    [
                        'code'    => 'PINT',
                        'convert' => [
                            'mul' => '0.00454609',
                            'div' => '8',
                        ],
                        'symbol'  => 'pt',
                    ],
                    [
                        'code'    => 'BARREL',
                        'convert' => [
                            'mul' => '0.16365924',
                        ],
                        'symbol'  => 'bbl',
                    ],
                    [
                        'code'    => 'GALLON',
                        'convert' => [
                            'mul' => '0.00454609',
                        ],
                        'symbol'  => 'gal',
                    ],
                    [
                        'code'    => 'CUBIC_FOOT',
                        'convert' => [
                            'mul' => '6.54119159',
                            'div' => '231',
                        ],
                        'symbol'  => 'ft³',
                    ],
                    [
                        'code'    => 'CUBIC_INCH',
                        'convert' => [
                            'mul' => '0.0037854118',
                            'div' => '231',
                        ],
                        'symbol'  => 'in³',
                    ],
                    [
                        'code'    => 'CUBIC_YARD',
                        'convert' => [
                            'mul' => '0.764554861',
                        ],
                        'symbol'  => 'yd³',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/weight',
                    ],
                ],
                'code'     => 'weight',
                'standard' => 'KILOGRAM',
                'units' => [
                    [
                        'code'    => 'MILLIGRAM',
                        'convert' => [
                            'mul' => '0.000001',
                        ],
                        'symbol'  => 'mg',
                    ],
                    [
                        'code'    => 'GRAM',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'g',
                    ],
                    [
                        'code'    => 'KILOGRAM',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'kg',
                    ],
                    [
                        'code'    => 'TON',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 't',
                    ],
                    [
                        'code'    => 'GRAIN',
                        'convert' => [
                            'mul' => '0.00006479891',
                        ],
                        'symbol'  => 'gr',
                    ],
                    [
                        'code'    => 'DENIER',
                        'convert' => [
                            'mul' => '0.001275',
                        ],
                        'symbol'  => 'denier',
                    ],
                    [
                        'code'    => 'ONCE',
                        'convert' => [
                            'mul' => '0.03059',
                        ],
                        'symbol'  => 'once',
                    ],
                    [
                        'code'    => 'MARC',
                        'convert' => [
                            'mul' => '0.24475',
                        ],
                        'symbol'  => 'marc',
                    ],
                    [
                        'code'    => 'LIVRE',
                        'convert' => [
                            'mul' => '0.4895',
                        ],
                        'symbol'  => 'livre',
                    ],
                    [
                        'code'    => 'OUNCE',
                        'convert' => [
                            'mul' => '0.45359237',
                            'div' => '16'
                        ],
                        'symbol'  => 'oz',
                    ],
                    [
                        'code'    => 'POUND',
                        'convert' => [
                            'mul' => '0.45359237',
                        ],
                        'symbol'  => 'lb',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/pressure',
                    ],
                ],
                'code'     => 'pressure',
                'standard' => 'BAR',
                'units' => [
                    [
                        'code'    => 'BAR',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'Bar',
                    ],
                    [
                        'code'    => 'PASCAL',
                        'convert' => [
                            'mul' => '0.00001',
                        ],
                        'symbol'  => 'Pa',
                    ],
                    [
                        'code'    => 'HECTOPASCAL',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'hPa',
                    ],
                    [
                        'code'    => 'MILLIBAR',
                        'convert' => [
                            'mul' => '0.001',
                        ],
                        'symbol'  => 'mBar',
                    ],
                    [
                        'code'    => 'ATM',
                        'convert' => [
                            'mul' => '0.986923',
                        ],
                        'symbol'  => 'atm',
                    ],
                    [
                        'code'    => 'PSI',
                        'convert' => [
                            'mul' => '14.50376985373022',
                        ],
                        'symbol'  => 'PSI',
                    ],
                    [
                        'code'    => 'TORR',
                        'convert' => [
                            'mul' => '750.06375541921',
                        ],
                        'symbol'  => 'Torr',
                    ],
                    [
                        'code'    => 'MMHG',
                        'convert' => [
                            'mul' => '750.06375541921',
                        ],
                        'symbol'  => 'mmHg',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/energy',
                    ],
                ],
                'code'     => 'energy',
                'standard' => 'JOULE',
                'units' => [
                    [
                        'code'    => 'JOULE',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'J',
                    ],
                    [
                        'code'    => 'CALORIE',
                        'convert' => [
                            'mul' => '4.184',
                        ],
                        'symbol'  => 'cal',
                    ],
                    [
                        'code'    => 'KILOCALORIE',
                        'convert' => [
                            'mul' => '4184',
                        ],
                        'symbol'  => 'kcal',
                    ],
                    [
                        'code'    => 'KILOJOULE',
                        'convert' => [
                            'mul' => '1000',
                        ],
                        'symbol'  => 'kJ',
                    ],
                ],
            ],
            [
                '_links'   => [
                    'self' => [
                        'href' => $baseUri . '/api/rest/v1/measure-families/casebox',
                    ],
                ],
                'code'     => 'casebox',
                'standard' => 'PIECE',
                'units' => [
                    [
                        'code'    => 'PIECE',
                        'convert' => [
                            'mul' => '1',
                        ],
                        'symbol'  => 'Pc',
                    ],
                    [
                        'code'    => 'DOZEN',
                        'convert' => [
                            'mul' => '12',
                        ],
                        'symbol'  => 'Dz',
                    ],
                ],
            ],
        ];
    }
}
