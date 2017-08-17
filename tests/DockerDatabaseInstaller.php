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
    /** @var string */
    protected $dockerName;

    /**
     * @param string $dockerName
     */
    public function __construct($dockerName)
    {
        $this->dockerName = $dockerName;
    }

    /**
     * {@inheritdoc}
     */
    public function install($path, $binPath)
    {
        $command = sprintf('docker exec %s php %s/%s/console pim:installer:db -e prod', $this->dockerName, $path, $binPath);

        $output = [];
        exec(escapeshellcmd($command), $output, $status);

        $output = implode(PHP_EOL, $output);

        if (0 !== $status) {
            throw new \RuntimeException(sprintf('An error occurred during the installation of the PIM. Output: %s', $output));
        }
    }
}
