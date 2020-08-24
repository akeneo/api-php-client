<?php

namespace Akeneo\Pim\ApiClient\Security;

/**
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Webhook
{
    const HTTP_HEADER_SIGNATURE = 'x-akeneo-signature';
    const HTTP_HEADER_TIMESTAMP = 'x-akeneo-timestamp';

    public static function createSignature(string $secret, string $body, int $timestamp): string
    {
        $data = (string)$timestamp . '.' . $body;

        return hash_hmac('sha256', $data, $secret);
    }

    public static function verifySignature(string $originalSignature, string $generatedSignature): bool
    {
        return hash_equals($originalSignature, $generatedSignature);
    }
}
