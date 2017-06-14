<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to install fixtures on PIM installation inside a docker.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DockerDatabaseInstaller implements DatabaseInstallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function install($path, array $options = [])
    {
        if (!isset($options['docker_name'])) {
            throw new \RuntimeException('Docker name should be specified in the parameter "options", none given.');
        }

        $command = sprintf('docker exec %s php %s/app/console pim:installer:db -e prod', $options['docker_name'], $path);

        $output = [];
        exec(escapeshellcmd($command), $output, $status);

        $output = implode(PHP_EOL, $output);

        if (0 !== $status) {
            throw new \RuntimeException(sprintf('An error occurred during the installation of the PIM. Output: %s', $output));
        }
    }
}
