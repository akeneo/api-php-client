<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Akeneo\Pim\Pagination\PageFactoryInterface;
use Akeneo\Pim\Pagination\PageInterface;
use Akeneo\Pim\Pagination\ResourceCursorFactoryInterface;
use Akeneo\Pim\Pagination\ResourceCursorInterface;

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
    public function get($familyCode, $familyVariantCode)
    {
        return $this->resourceClient->getResource(static::FAMILY_VARIANT_URI, [$familyCode, $familyVariantCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function listPerPage($familyCode, $limit = 10, $withCount = false, array $queryParameters = [])
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
    public function all($familyCode, $pageSize = 10, array $queryParameters = [])
    {
        $firstPage = $this->listPerPage($familyCode, $pageSize, false, $queryParameters);

        return $this->cursorFactory->createCursor($pageSize, $firstPage);
    }
}
