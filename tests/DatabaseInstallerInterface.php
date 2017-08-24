<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to install fixtures into the PIM in order to reset the database between the test.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface DatabaseInstallerInterface
{
    /**
     * Install the database of the PIM.
     *
     * @param string $path    path to Akeneo PIM application
     * @param string $binPath path to Akeneo PIM binaries
     *
     * @throws \RuntimeException if an error occured during the PIM install
     */
    public function install($path, $binPath);
}
