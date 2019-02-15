<?php

namespace Akeneo\Pim\ApiClient\Api;

use Akeneo\Pim\ApiClient\Client\ResourceClientInterface;
use Akeneo\Pim\ApiClient\Exception\InvalidArgumentException;
use Akeneo\Pim\ApiClient\Pagination\PageFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\PageInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\ApiClient\Pagination\ResourceCursorInterface;

/**
 * Api implementation to manages Family Variants
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FamilyVariantApi implements FamilyVariantApiInterface
{
    const FAMILY_VARIANT_URI = 'api/rest/v1/families/%s/variants/%s';
    const FAMILY_VARIANTS_URI = 'api/rest/v1/families/%s/variants';

    /** @var ResourceClientInterface */
    protected $resourceClient;

    /** @var PageFactoryInterface */
    protected $pageFactory;

    /** @var ResourceCursorFactoryInterface */
    protected $cursorFactory;

    /**
     * @param ResourceClientInterface        $resourceClient
     * @param PageFactoryInterface           $pageFactory
     * @param ResourceCursorFactoryInterface $cursorFactory
     */
    public function __construct(
        ResourceClientInterface $resourceClient,
        PageFactoryInterface $pageFactory,
        ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->resourceClient = $resourceClient;
        $this->pageFactory = $pageFactory;
        $this->cursorFactory = $cursorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($familyCode, $familyVariantCode): array
    {
        return $this->resourceClient->getResource(static::FAMILY_VARIANT_URI, [$familyCode, $familyVariantCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function create($familyCode, $familyVariantCode, array $data = []): int
    {
        if (array_key_exists('family', $data)) {
            throw new InvalidArgumentException('The parameter "family" must not be defined in the data parameter');
        }
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" must not be defined in the data parameter');
        }
        $data['code'] = $familyVariantCode;

        return $this->resourceClient->createResource(static::FAMILY_VARIANTS_URI, [$familyCode], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($familyCode, $familyVariantCode, array $data = []): int
    {
        if (array_key_exists('family', $data)) {
            throw new InvalidArgumentException('The parameter "family" must not be defined in the data parameter');
        }
        if (array_key_exists('code', $data)) {
            throw new InvalidArgumentException('The parameter "code" must not be defined in the data parameter');
        }
        $data['code'] = $familyVariantCode;

        return $this->resourceClient->upsertResource(static::FAMILY_VARIANT_URI, [$familyCode, $familyVariantCode], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function listPerPage($familyCode, $limit = 10, $withCount = false, array $queryParameters = []): PageInterface
    {
        $data = $this->resourceClient->getResources(
            static::FAMILY_VARIANTS_URI,
            [$familyCode],
            $limit,
            $withCount,
            $queryParameters
        );

        return $this->pageFactory->createPage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function all($familyCode, $pageSize = 10, array $queryParameters = []): ResourceCursorInterface
    {
        $firstPage = $this->listPerPage($familyCode, $pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertList($familyCode, $familyVariants): \Traversable
    {
        return $this->resourceClient->upsertStreamResourceList(static::FAMILY_VARIANTS_URI, [$familyCode], $familyVariants);
    }
}
