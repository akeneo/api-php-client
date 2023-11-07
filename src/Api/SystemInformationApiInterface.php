<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Api\Operation\GettableResourceInterface;
use Akeneo\Pim\ApiClient\Api\Operation\ListableResourceInterface;
use Akeneo\Pim\ApiClient\Exception\HttpException;

/**
 * API to manage system information.
 */
interface SystemInformationApiInterface
{
    /**
     * Gets the system information
     *
     * @throws HttpException If the request failed.
     * @return array
     */
    public function get(): array;
}
