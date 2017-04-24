<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Routing\UriGeneratorInterface;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryApi implements CategoryApiInterface
{
    const CATEGORIES_PATH = 'api/rest/v1/categories';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /** @var UriGeneratorInterface */
    protected $uriGenerator;

    /**
     * @param ResourceClientInterface $resourceClient
     * @param UriGeneratorInterface   $uriGenerator
     */
    public function __construct(ResourceClientInterface $resourceClient, UriGeneratorInterface $uriGenerator)
    {
        $this->resourceClient = $resourceClient;
        $this->uriGenerator = $uriGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories($limit = null, $withCount = null, array $additionalQueryParameters = [])
    {
        if (array_key_exists('limit', $additionalQueryParameters)) {
            throw new \InvalidArgumentException('The parameter "limit" should not be defined in the additional query parameters');
        }

        if (array_key_exists('with_count', $additionalQueryParameters)) {
            throw new \InvalidArgumentException('The parameter "with_count" should not be defined in the additional query parameters');
        }

        $parameters = $additionalQueryParameters;

        if (null !== $limit) {
            $parameters['limit'] = $limit;
        }

        if (null !== $withCount) {
            $parameters['with_count'] = $withCount;
        }

        $uri = $this->uriGenerator->generate(static::CATEGORIES_PATH, [], $parameters);

        return $this->resourceClient->getResource($uri);
    }
}
