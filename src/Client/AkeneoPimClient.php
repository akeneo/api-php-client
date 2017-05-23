<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\AttributeApiInterface;
use Akeneo\Pim\Api\AttributeOptionApiInterface;
use Akeneo\Pim\Api\CategoryApiInterface;
use Akeneo\Pim\Api\FamilyApiInterface;

/**
 * This class is the implementation of the client to use the Akeneo PIM API.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    /** @var CategoryApiInterface */
    protected $categoryApi;

    /** @var AttributeApiInterface */
    protected $attributeApi;

    /** @var AttributeOptionApiInterface */
    protected $attributeOptionApi;

    /** @var FamilyApiInterface */
    protected $familyApi;

    /**
     * @param CategoryApiInterface        $categoryApi
     * @param AttributeApiInterface       $attributeApi
     * @param AttributeOptionApiInterface $attributeOptionApi
     * @param FamilyApiInterface          $familyApi
     */
    public function __construct(
        CategoryApiInterface $categoryApi,
        AttributeApiInterface $attributeApi,
        AttributeOptionApiInterface $attributeOptionApi,
        FamilyApiInterface $familyApi
    )
    {
        $this->categoryApi = $categoryApi;
        $this->attributeApi = $attributeApi;
        $this->attributeOptionApi = $attributeOptionApi;
        $this->familyApi = $familyApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryApi()
    {
        return $this->categoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeApi()
    {
        return $this->attributeApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeOptionApi()
    {
        return $this->attributeOptionApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getFamilyApi()
    {
        return $this->familyApi;
    }
}
