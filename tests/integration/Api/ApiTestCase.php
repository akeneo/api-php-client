<?php

namespace Akeneo\Pim\tests\integration\Api;

use Akeneo\Pim\Client\AkeneoPimClientBuilder;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use SebastianBergmann\Exporter\Exporter;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var AkeneoPimClientInterface */
    protected $pimClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $clientBuilder = new AkeneoPimClientBuilder(
            'http://akeneo-pim.local',
            '1_2c7q3pt2kckksckc0wcc4sokcg4swg4kcggo04skg8kkok40w',
            '162krw689itcsow4gksksk4ss8wkco40kg0kkoss40884swgs4',
            'admin',
            'admin'
        );

        $this->pimClient = $clientBuilder->build();
    }

    /**
     * @param array $expectedResponseBody
     * @param array $actualResponseBody
     */
    protected function assertSameResponseBody(array $expectedResponseBody, array $actualResponseBody)
    {
        $differences = $this->computeArrayDiff($expectedResponseBody, $actualResponseBody);

        if (! empty($differences)) {
            $this->fail(
                'Failed asserting that the response has the expected body.'
                .PHP_EOL
                .'Differences between expected and actual body :'
                .PHP_EOL
                .var_export($differences, true)
            );
        }
    }

    /**
     * @param array $expectedValues
     * @param array $actualValues
     *
     * @return array
     */
    protected function computeArrayDiff(array $expectedValues, array $actualValues)
    {
        $differences = [];

        foreach ($expectedValues as $key => $expectedValue) {
            if (!array_key_exists($key, $actualValues)) {
                $differences[$key] = $expectedValue;
            } elseif (is_array($expectedValue)) {
                if (is_array($actualValues[$key])) {
                    $embeddedDifferences = $this->computeArrayDiff($expectedValue, $actualValues[$key]);
                    if (!empty($embeddedDifferences)) {
                        $differences[$key] = $embeddedDifferences;
                    }
                } else {
                    $differences[$key] = $expectedValue;
                }
            } elseif ($expectedValue !== $actualValues[$key]) {
                $differences[$key] = $expectedValue;
            }
        }

        return $differences;
    }
}
