<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to generate the couple client id/secret on the PIM.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface CredentialGeneratorInterface
{
    /**
     * Generates credentials on the PIM.
     *
     * @param string $path       path to Akeneo PIM application
     * @param string $binPath    path to Akeneo PIM binaries
     * @param string $pimVersion Akeneo PIM version
     *
     * @throws \RuntimeException if an error occured during the generation process
     *
     * @return array credentials on the form ['client_id' => 'client', 'secret' => 'secret']
     */
    public function generate($path, $binPath, $pimVersion);
}
