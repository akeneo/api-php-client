<?php

namespace Akeneo\Pim\Api;

use Akeneo\Pim\Client\ResourceClientInterface;

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

    /** @var ResourceClientInterface $resourceClient */
    protected $resourceClient;

    /**
     * @param ResourceClientInterface $resourceClient
     */
    public function __construct(ResourceClientInterface $resourceClient)
    {
        $this->resourceClient = $resourceClient;
    }

    /**
     * {@inheritdoc}
     */
    public function get($familyCode, $familyVariantCode)
    {
        return $this->resourceClient->getResource(static::FAMILY_VARIANT_URI, [$familyCode, $familyVariantCode]);
    }
}
