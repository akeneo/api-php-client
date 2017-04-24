<?php

namespace tests\integration\Akeneo\Pim\Api;

use Akeneo\Pim\Api\CategoryApi;
use Akeneo\Pim\Routing\Route;
use Akeneo\Pim\Routing\UriGenerator;

/**
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryApiIntegration extends TestCase
{
    public function testGetCategories()
    {
        $httpClient = $this->createHttpClientMock();
        $uriGenerator = new UriGenerator('http://akeneo-pim.local');

        $resourceClient = $this->getResourceClient($httpClient, $uriGenerator);
        $categoryApi = new CategoryApi($resourceClient, $uriGenerator);

        $expectedCategories = [
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

        $httpClient->addResponse($this->createResponseMock(200, json_encode($expectedCategories)));

        $this->assertEquals($expectedCategories, $categoryApi->getCategories(10));
    }
}
