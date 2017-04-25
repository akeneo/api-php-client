<?php

namespace Akeneo\Pim\Client;

use Akeneo\Pim\Api\CategoryApiInterface;

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

    /**
     * @param CategoryApiInterface $categoryApi
     */
    public function __construct(CategoryApiInterface $categoryApi)
    {
        $this->categoryApi = $categoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryApi()
    {
        return $this->categoryApi;
    }
}
