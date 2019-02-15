<?php

namespace Akeneo\Pim\ApiClient\Pagination;

use Akeneo\Pim\ApiClient\Client\HttpClientInterface;

/**
 * Factory to create a page object representing a list of resources.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class PageFactory implements PageFactoryInterface
{
    /** @var HttpClientInterface */
    protected $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function createPage(array $data): PageInterface
    {
        $nextLink = isset($data['_links']['next']['href']) ? $data['_links']['next']['href'] : null;
        $previousLink = isset($data['_links']['previous']['href']) ? $data['_links']['previous']['href'] : null;
        $firstLink = $data['_links']['first']['href'];

        $count = isset($data['items_count']) ? $data['items_count'] : null;

        $items = $data['_embedded']['items'];

        return new Page(new PageFactory($this->httpClient), $this->httpClient, $firstLink, $previousLink, $nextLink, $count, $items);
    }
}
