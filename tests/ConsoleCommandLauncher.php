<?php

namespace Akeneo\Pim\ApiClient\tests;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Launches a command through a Symfony process.
 *
 * The launcher will decide if the command is to be launched locally or in a
 * Docker container depending to the configuration passed to the constructor.
 *
 * @author    Damien Carcel <damien.carcel@gmail.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ConsoleCommandLauncher
{
    /** @var array */
    private $configuration;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Launches a command and returns its output.
     *
     * @param string $command
     *
     * @throws ProcessFailedException
     *
     * @return string
     */
    public function launch($command)
    {
        $process = new Process(sprintf('%s %s', $this->getPimConsole(), $command));
        $process->mustRun();

        return $process->getOutput();
    }

    /**
     * Prepares the execution of the PIM console script.
     *
     * @return string
     */
    private function getPimConsole()
    {
        $installPath = $this->configuration['pim']['install_path'];
        $binPath = $this->configuration['pim']['bin_path'];

        if (true === $this->configuration['pim']['is_docker']) {
            $container = $this->configuration['pim']['docker_name'];


            return sprintf('docker exec %s %s/%s/console', $container, $installPath, $binPath);
        }

        return sprintf('%s/%s/console', $installPath, $binPath);
    }
}
