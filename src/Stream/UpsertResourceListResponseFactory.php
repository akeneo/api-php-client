<?php

namespace Akeneo\Pim\ApiClient\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Factory to create an UpsertResourceListResponse object.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpsertResourceListResponseFactory
{
    public function create(StreamInterface $bodyStream):  UpsertResourceListResponse
    {
        return new UpsertResourceListResponse($bodyStream, new LineStreamReader());
    }
}
