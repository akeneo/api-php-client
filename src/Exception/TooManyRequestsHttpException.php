<?php

namespace Akeneo\Pim\ApiClient\Exception;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class TooManyRequestsHttpException extends ClientErrorHttpException
{
    /**
     * How much time must be waited before next request. Result in seconds.
     */
    public function getRetryAfter(): int
    {
        $response = $this->getResponse();

        if (!$response->hasHeader('Retry-After')) {
            throw new RuntimeException('Cannot find Retry-After header.');
        }

        $retryAfter = $response->getHeader('Retry-After')[0];

        if (preg_match('/^\d+$/', $retryAfter)) {
            return (int) $retryAfter;
        }

        throw new RuntimeException('Cannot parse Retry-After header. Value must be seconds.');
    }

    protected function getAdditionalInformationMessage(): string
    {
        return '(see https://api.akeneo.com/php-client/exception.html#too-many-requests-exception)';
    }
}
