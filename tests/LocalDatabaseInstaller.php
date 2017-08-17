<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to install fixtures on a local PIM installation.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class LocalDatabaseInstaller implements DatabaseInstallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function install($path, $binPath)
    {
        if (!is_dir($path)) {
            throw new \RuntimeException(sprintf('Parameter "path" is not a directory or does not exist, "%s" given.', $path));
        }

        $command = sprintf('php %s/%s/console pim:installer:db -e prod', $path, $binPath);

        $output = [];
        exec(escapeshellcmd($command), $output, $status);

        $output = implode(PHP_EOL, $output);

        if (0 !== $status) {
            throw new \RuntimeException(sprintf('An error occurred during the installation of the PIM. Output: %s', $output));
        }
    }
}
