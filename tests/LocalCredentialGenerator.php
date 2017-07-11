<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to generate the couple client/secret id on a local PIM installation.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class LocalCredentialGenerator implements CredentialGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate($path)
    {
        return ['client_id' => '1_49aow5won1k48og8skwowk8ockg44sw00ksoco000koo80s4s', 'secret' => '3h0pvtudfqm88cgcwwsok40ggk8ok8c8c0s0g8o8w8wgowggk4'];

        if (!is_dir($path)) {
            throw new \RuntimeException(sprintf('Parameter "path" is not a directory or does not exist, "%s" given.', $path));
        }

        $command = sprintf('php %s/app/console pim:oauth-server:create-client -e prod', $path);

        $output = [];
        exec(escapeshellcmd($command), $output);

        if (count($output) !== 3) {
            throw new \RuntimeException('An error occurred during the generation of the client id and secret.');
        }

        preg_match('/client_id: (.+)$/', $output[1], $client);
        preg_match('/secret: (.+)$/', $output[2], $secret);

        if (!isset($client[1]) || !isset($secret[1])) {
            throw new \RuntimeException('An error occurred when getting client id and secret from the generation process output.');
        }

        return ['client_id' => $client[1], 'secret' => $secret[1]];
    }
}
