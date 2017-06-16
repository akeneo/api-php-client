<?php

namespace Akeneo\Pim\tests;

/**
 * Aims to generate the couple client/secret id on a PIM installation inside a docker.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DockerCredentialGenerator implements CredentialGeneratorInterface
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
    public function generate($path)
    {
        if (!isset($options['docker_name'])) {
            throw new \RuntimeException('Docker name should be specified in the parameter "options", none given.');
        }

        $command = sprintf('docker exec %s php %s/app/console pim:oauth-server:create-client -e prod', $this->dockerName, $path);

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
