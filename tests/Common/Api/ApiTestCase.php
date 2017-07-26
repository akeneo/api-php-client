<?php

namespace Akeneo\Pim\tests\Common\Api;

use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\tests\DockerCredentialGenerator;
use Akeneo\Pim\tests\DockerDatabaseInstaller;
use Akeneo\Pim\tests\LocalCredentialGenerator;
use Akeneo\Pim\tests\LocalDatabaseInstaller;
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
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $config = $this->getConfiguration();

        $installer = new LocalDatabaseInstaller();
        if (true === $config['pim']['is_docker']) {
            $installer = new DockerDatabaseInstaller($config['pim']['docker_name']);
        }

        $installer->install($config['pim']['install_path']);
    }

    /**
     * @return AkeneoPimClientInterface
     */
    protected function createClient()
    {
        $config = $this->getConfiguration();
        $generator = new LocalCredentialGenerator();
        if (true === $config['pim']['is_docker']) {
            $generator = new DockerCredentialGenerator($config['pim']['docker_name']);
        }

        $credentials = $generator->generate($config['pim']['install_path']);
        $clientBuilder = new AkeneoPimClientBuilder($config['api']['baseUri']);

        return $clientBuilder->buildAuthenticatedByPassword(
            $credentials['client_id'],
            $credentials['secret'],
            $config['api']['credentials']['username'],
            $config['api']['credentials']['password']
        );
    }

    /**
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function getConfiguration()
    {
        $configFile = realpath(dirname(__FILE__)).'/../../../etc/parameters.yml';
        if (!is_file($configFile)) {
            throw new \RuntimeException('The configuration file parameters.yml is missing');
        }

        $config = Yaml::parse(file_get_contents($configFile));

        return $config;
    }

    /**
     * @return StreamFactory
     */
    public function getStreamFactory()
    {
        return StreamFactoryDiscovery::find();
    }

    /**
     * Assert that all the expected data of a content of a resource are the same in an actual one.
     * An associative array can contain more elements than expected, but an numeric key array must be strictly identical.
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
