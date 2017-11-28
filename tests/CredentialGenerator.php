<?php

namespace Akeneo\Pim\ApiClient\tests;

/**
 * Aims to generate the couple client/secret id on a PIM installation.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CredentialGenerator
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
     * Generates credentials on the PIM.
     *
     * @param string $pimVersion Akeneo PIM version
     *
     * @throws \RuntimeException If it was not possible to get the client id and
     *                           secrets from the process output
     *
     * @return array credentials on the form ['client_id' => 'client', 'secret' => 'secret']
     */
    public function generate($pimVersion)
    {
        $label = "1.7" === $pimVersion ? '--label="PHP client credentials"' : '"PHP client credentials"';
        $command = sprintf('pim:oauth-server:create-client %s -e prod', $label);

        $output = $this->consoleCommandLauncher->launch($command);

        preg_match('/client_id: (.+)/', $output, $client);
        preg_match('/secret: (.+)/', $output, $secret);

        if (!isset($client[1]) || !isset($secret[1])) {
            throw new \RuntimeException(sprintf(
                'An error occurred when getting client id and secret from the generation process output: "%s"',
                $output
            ));
        }

        return ['client_id' => $client[1], 'secret' => $secret[1]];
    }
}
