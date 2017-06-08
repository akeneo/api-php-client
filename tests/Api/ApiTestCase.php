<?php

namespace Akeneo\Pim\tests\Api;

use Akeneo\Pim\Client\AkeneoPimClientBuilder;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return AkeneoPimClientInterface
     */
    protected function createClient()
    {
        $config = $this->getApiConfiguration();
        $credentials = $config['credentials'];

        $clientBuilder = new AkeneoPimClientBuilder(
            $config['baseUri'],
            $credentials['clientId'],
            $credentials['secret'],
            $credentials['username'],
            $credentials['password']
        );

        return $clientBuilder->build();
    }

    /**
     * @return array
     */
    protected function getApiConfiguration()
    {
        $configFile = realpath(dirname(__FILE__)).'/../../etc/parameters.yml';
        if (!is_file($configFile)) {
            throw new \RuntimeException('The configuration file parameters.yml is missing');
        }

        $config = Yaml::parse(file_get_contents($configFile));

        return $config['api'];
    }

    /**
     * Assert that all the expected data of a response body are the same in an actual one.
     * The actual response body can contains more data than the expected one.
     *
     * @param array $expectedResponseBody
     * @param array $actualResponseBody
     */
    public function assertSameResponseBody(array $expectedResponseBody, array $actualResponseBody)
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
