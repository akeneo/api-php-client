<?php

namespace Akeneo\Pim\Routing;

use Psr\Http\Message\UriInterface;

/**
 * Interface to generate a complete uri from the path to the endpoint.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface UriGeneratorInterface
{
    /**
     * Generate an uri from a path, by adding host and port.
     *
     * @param $path path of the endpoint
     *
     * @return UriInterface
     */
    public function generate($path);
}
