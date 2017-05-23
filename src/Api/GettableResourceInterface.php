<?php

namespace Akeneo\Pim\Api;

/**
 * API that can fetch a single resource
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface GettableResourceInterface
{
    /**
     * Gets a resource by its code
     *
     * @param string $code Code of the resource
     *
     * @throws HttpException
     *
     * @return array
     */
    public function get($code);
}
