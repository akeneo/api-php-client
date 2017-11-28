<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\tests\ConsoleCommandLauncher;
use Akeneo\Pim\ApiClient\tests\CredentialGenerator;
use Akeneo\Pim\ApiClient\tests\DatabaseInstaller;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\StreamFactory;
use Symfony\Component\Yaml\Yaml;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $configuration;

    /** @var ConsoleCommandLauncher */
    private $consoleCommandLauncher;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->configuration = $this->parseConfigurationFile();
        $this->consoleCommandLauncher = new ConsoleCommandLauncher($this->getConfiguration());

        $installer = new DatabaseInstaller($this->getCommandLauncher());
        $installer->install();
    }

    /**
     * @return StreamFactory
     */
    protected function getStreamFactory()
    {
        return StreamFactoryDiscovery::find();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return AkeneoPimClientInterface
     */
    protected function createClient($username = 'admin', $password = 'admin')
    {
        $config = $this->getConfiguration();
        $generator = new CredentialGenerator($this->getCommandLauncher());

        $credentials = $generator->generate($config['pim']['version']);
        $clientBuilder = new AkeneoPimClientBuilder($config['pim']['base_uri']);

        return $clientBuilder->buildAuthenticatedByPassword(
            $credentials['client_id'],
            $credentials['secret'],
            $username,
            $password
        );
    }

    /**
     * @return ConsoleCommandLauncher
     */
    protected function getCommandLauncher()
    {
        return $this->consoleCommandLauncher;
    }

    /**
     * @return array
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Assert that all the expected data of a content of a resource are the same
     * in an actual one.
     * An associative array can contain more elements than expected, but an
     * numeric key array must be strictly identical.
     *
     * @param array $expectedContent
     * @param array $actualContent
     */
    protected function assertSameContent(array $expectedContent, array $actualContent)
    {
        $expectedContent = $this->sortResourceContent($expectedContent);
        $actualContent = $this->sortResourceContent($actualContent);

        $expectedContent = $this->mergeResourceContents($actualContent, $expectedContent);

        $this->assertSame($expectedContent, $actualContent);
    }

    /**
     * @return string
     */
    protected function getConfigurationFile()
    {
        return realpath(dirname(__FILE__)).'/../../../tests/etc/parameters.yml';
    }

    /**
     * @throws \RuntimeException
     *
     * @return array
     */
    private function parseConfigurationFile()
    {
        $configFile = $this->getConfigurationFile();
        if (!is_file($configFile)) {
            throw new \RuntimeException('The configuration file parameters.yml is missing');
        }

        $config = Yaml::parse(file_get_contents($configFile));

        return $config;
    }

    /**
     * Recursively merge an expected content in a actual one to be able to compare them.
     * Numeric key arrays are kept identical.
     *
     * @param array $actualContent
     * @param array $expectedContent
     *
     * @return array
     */
    private function mergeResourceContents(array $actualContent, array $expectedContent)
    {
        foreach ($expectedContent as $key => $value) {
            if (is_array($value) && isset($actualContent[$key]) && is_array($actualContent[$key])) {
                $expectedContent[$key] = $this->mergeResourceContents($actualContent[$key], $expectedContent[$key]);
            }
        }

        if ($this->isAssociativeArray($expectedContent)) {
            $mergedContent = array_merge($actualContent, $expectedContent);
        } else {
            $mergedContent = $expectedContent;
        }

        return $mergedContent;
    }

    /**
     * @param array $array
     *
     * @return bool True if the array is associative (i.e. at least one key is a string)
     */
    private function isAssociativeArray(array $array)
    {
        foreach (array_keys($array) as $key) {
            if (is_string($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sort a resource content to be able to compare it with another one.
     * The order of elements in an associative array is important in PHPUnit but not for us.
     * So we force the order of the associative arrays to be identical to be able to use them in a PHPUnit assertion.
     * This sort has no consequences for sequential arrays with numeric keys.
     *
     * @param array $resourceContent
     *
     * @return array Sorted resource content
     */
    private function sortResourceContent(array $resourceContent)
    {
        ksort($resourceContent);

        foreach ($resourceContent as $key => $value) {
            if (is_array($value)) {
                $resourceContent[$key] = $this->sortResourceContent($value);
            }
        }

        return $resourceContent;
    }
}
