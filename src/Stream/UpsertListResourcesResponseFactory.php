<?php

namespace Akeneo\Pim\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Factory to create an UpsertListResourcesResponse object.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpsertListResourcesResponseFactory
{
    public function create(StreamInterface $bodyStream)
    {
        return new UpsertListResourcesResponse($bodyStream);
    }

}
