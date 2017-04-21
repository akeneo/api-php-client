<?php

namespace Akeneo\Pim\Client;

/**
 * Client to interact with the API resources
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ResourceClientInterface
{
    /**
     * Get a resource
     *
     * @param string $uri     URI of the resource
     * @param array  $headers Optional headers to add to the HTTP request
     *
     * @return mixed
     */
    public function getResource($uri, array $headers = []);
}
