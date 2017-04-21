<?php

namespace spec\Akeneo\Pim\Api;

use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Client\ResourceClientInterface;
use Akeneo\Pim\Routing\Route;
use Akeneo\Pim\Routing\UriGeneratorInterface;
use PhpSpec\ObjectBehavior;

class CategoryApiSpec extends ObjectBehavior
{
    function let(ResourceClientInterface $resourceClient, UriGeneratorInterface $uriGenerator)
    {
        $this->beConstructedWith($resourceClient, $uriGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CategoryApi::class);
    }

    function it_returns_a_list_of_categories_without_having_defined_parameters($resourceClient, $uriGenerator)
    {
        $uri = 'http://akeneo-pim.local/api/rest/v1/categories';
        $categories = $this->getSampleOfCategories();

        $uriGenerator
            ->generate(CategoryApi::CATEGORIES_PATH, [], [])
            ->shouldBeCalled()
            ->willReturn($uri);

        $resourceClient
            ->getResource($uri)
            ->shouldBeCalled()
            ->willReturn($categories);

        $this->getCategories()->shouldReturn($categories);
    }

    function it_returns_a_list_of_categories_having_defined_parameters($resourceClient, $uriGenerator)
    {
        $uri = 'http://akeneo-pim.local/api/rest/v1/categories?limit=5&with_count=true';
        $categories = $this->getSampleOfCategories();
        $limit = 5;
        $withCount = true;
        $parameters = ['misc' => 1];

        $uriGenerator
            ->generate(CategoryApi::CATEGORIES_PATH, [], ['limit' => $limit, 'with_count' => $withCount, 'misc' => 1])
            ->shouldBeCalled()
            ->willReturn($uri);

        $resourceClient
            ->getResource($uri)
            ->shouldBeCalled()
            ->willReturn($categories);

        $this->getCategories($limit, $withCount, $parameters)->shouldReturn($categories);
    }

    function it_throws_an_exception_if_limit_is_defined_in_additional_parameters_to_get_categories()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('getCategories', [10, null, ['limit' => null]]);
    }

    function it_throws_an_exception_if_with_count_is_defined_in_additional_parameters_to_get_categories()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('getCategories', [null, false, ['with_count' => null]]);
    }

    protected function getSampleOfCategories()
    {
        return [
            '_links'  => [
                'self'  => [
                    'href' => 'http://akeneo-pim.local/api/rest/v1/categories?page=1&limit=10&with_count=false',
                ],
                'first' => [
                    'href' => 'http://akeneo-pim.local/api/rest/v1/categories?page=1&limit=10&with_count=false',
                ],
                'next'  => [
                    'href' => 'http://akeneo-pim.local/api/rest/v1/categories?page=2&limit=10&with_count=false',
                ],
            ],
            'current_page' => 1,
            '_embedded'    => [
                'items' => [
                    [
                        '_links' => [
                            'self' => [
                                'href' => 'http://akeneo-pim.local/api/rest/v1/categories/master',
                            ],
                        ],
                        'code'   => 'master',
                        'parent' => null,
                        'labels' => [
                            'en_US' => 'Master catalog',
                            'de_DE' => 'Hauptkatalog',
                            'fr_FR' => 'Catalogue principal',
                        ],
                    ],
                    [
                        '_links' => [
                            'self' => [
                                'href' => 'http://akeneo-pim.local/api/rest/v1/categories/audio_video',
                            ],
                        ],
                        'code'   => 'audio_video',
                        'parent' => 'master',
                        'labels' => [
                            'en_US' => 'Audio and Video',
                            'de_DE' => 'Audio und Video',
                            'fr_FR' => 'Audio et Video',
                        ],
                    ],
                ],
            ],
        ];
    }
}
