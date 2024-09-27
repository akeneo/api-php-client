<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;

/**
 * API implementation to manage system information.
 */
class SystemInformationApi implements SystemInformationApiInterface
{
    public const SYSTEM_INFORMATION_URI = 'api/rest/v1/system-information';

    public function __construct(
        protected ResourceClientInterface $resourceClient,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->resourceClient->getResource(static::SYSTEM_INFORMATION_URI);
    }
}
