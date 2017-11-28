<?php

namespace Akeneo\Pim\ApiClient\tests;

/**
 * Aims to install fixtures on a local PIM installation.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DatabaseInstaller
{
    /** @var ConsoleCommandLauncher */
    private $consoleCommandLauncher;

    /**
     * @param ConsoleCommandLauncher $consoleCommandLauncher
     */
    public function __construct(ConsoleCommandLauncher $consoleCommandLauncher)
    {
        $this->consoleCommandLauncher = $consoleCommandLauncher;
    }

    /**
     * Installs the PIM database.
     */
    public function install()
    {
        $this->consoleCommandLauncher->launch('pim:installer:db -e prod');
    }
}
