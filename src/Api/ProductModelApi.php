<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;

/**
 * API implementation to manage the product models.
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductModelApi implements ProductModelApiInterface
{
    const PRODUCT_MODEL_URI = 'api/rest/v1/product-models/%s';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /**
     * @param ResourceClientInterface $resourceClient
     */
    public function __construct(ResourceClientInterface $resourceClient)
    {
        $this->resourceClient = $resourceClient;
    }

    /**
     * Available from Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function get($code)
    {
        return $this->resourceClient->getResource(static::PRODUCT_MODEL_URI, [$code]);
    }
}
