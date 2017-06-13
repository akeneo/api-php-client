<?php

namespace Akeneo\Pim\tests\Api;

use Akeneo\Pim\Client\AkeneoPimClientBuilder;
use Akeneo\Pim\Client\AkeneoPimClientInterface;
use Akeneo\Pim\tests\DockerCredentialGenerator;
use Akeneo\Pim\tests\DockerDatabaseInstaller;
use Akeneo\Pim\tests\LocalCredentialGenerator;
use Akeneo\Pim\tests\LocalDatabaseInstaller;
use Symfony\Component\Yaml\Yaml;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $config = $this->getConfiguration();

        $installer = new LocalDatabaseInstaller();
        if (true === $config['pim']['is_docker']) {
            $installer = new DockerDatabaseInstaller();
        }

        $installer->install($config['pim']['install_path'], ['docker_name' => $config['pim']['docker_name']]);
    }

    /**
     * @return AkeneoPimClientInterface
     */
    protected function createClient()
    {
        $config = $this->getConfiguration();
        $generator = new LocalCredentialGenerator();
        if (true === $config['pim']['is_docker']) {
            $generator = new DockerCredentialGenerator();
        }

        $credentials = $generator->generate($config['pim']['install_path'], ['docker_name' => $config['pim']['docker_name']]);
        $clientBuilder = new AkeneoPimClientBuilder($config['api']['baseUri']);

        return $clientBuilder->buildAuthenticatedByPassword(
            $credentials['client_id'],
            $credentials['secret'],
            $config['api']['credentials']['username'],
            $config['api']['credentials']['password']
        );
    }

    /**
     * @return UriGeneratorInterface
     */
    protected function getUriGenerator()
    {
        $config = $this->getConfiguration();

        return new UriGenerator($config['api']['baseUri']);
    }

    /**
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function getConfiguration()
    {
        $configFile = realpath(dirname(__FILE__)).'/../../etc/parameters.yml';
        if (!is_file($configFile)) {
            throw new \RuntimeException('The configuration file parameters.yml is missing');
        }

        $config = Yaml::parse(file_get_contents($configFile));

        return $config;
    }

    /**
     * Assert that all the expected data of a content of a resource are the same in an actual one.
     * The actual content can contains more data than the expected one.
     *
     * @param array $expectedContent
     * @param array $actualContent
     */
    public function assertSameContent(array $expectedContent, array $actualContent)
    {
        $differences = $this->computeArrayDiff($expectedContent, $actualContent);

        if (! empty($differences)) {
            $this->fail(
                'Failed asserting that the content has the expected body.'
                .PHP_EOL
                .'Differences between expected and actual content :'
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
