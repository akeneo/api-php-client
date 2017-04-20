<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Routing\Route;
use Akeneo\Pim\Routing\UriGeneratorInterface;

/**
 * Class CategoryApi
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryApi implements CategoryApiInterface
{
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
    public function getCategories($limit = null, $withCount = null, array $parameters = [])
    {
        if (array_key_exists('limit', $parameters)) {
            throw new \InvalidArgumentException('The parameter "limit" should not be directly defined in parameters');
        }

        if (array_key_exists('with_count', $parameters)) {
            throw new \InvalidArgumentException('The parameter "with_count" should not be directly defined in parameters');
        }

        if (null !== $limit) {
            $parameters['limit'] = $limit;
        }

        if (null !== $withCount) {
            $parameters['with_count'] = $withCount;
        }

        $uri = $this->uriGenerator->generate(Route::CATEGORIES, $parameters);

        return $this->resourceClient->getResource($uri);
    }
}
