<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Http\Promise\Promise;
use Psr\Http\Client\ClientInterface as BaseClientInterface;
use Psr\Http\Message\RequestInterface;

interface ClientInterface extends BaseClientInterface
{
    public function sendAsyncRequest(RequestInterface $request): Promise;
}
