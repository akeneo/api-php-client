<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;
use Akeneo\Pim\tests\ValuesSanitizer;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractProductApiTestCase extends ApiTestCase
{
    /**
     * Replaces changing data by specified values.
     *
     * @param array $productData
     *
     * @return array
     */
    protected function sanitizeProductData(array $productData)
    {
        return ValuesSanitizer::sanitize($productData);
    }
}
