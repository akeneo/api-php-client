<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\InvalidArgumentException;

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
    const CREATE_PRODUCT_MODEL_URI = 'api/rest/v1/product-models';

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

    /**
     * Available from Akeneo PIM 2.0.
     *
     * {@inheritdoc}
     */
    public function create($code, array $data = [])
    {
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" must not be defined in the data parameter');
        }

        $data['code'] = $code;

        return $this->resourceClient->createResource(static::CREATE_PRODUCT_MODEL_URI, [], $data);
    }
}
