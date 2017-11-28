<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Category;

use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class CreateCategoryIntegration extends ApiTestCase
{
    public function testCreate()
    {
        $api = $this->createClient()->getCategoryApi();
        $response = $api->create('pumps', [
            'parent' => '2014_collection',
            'labels' => [
                'en_US' => 'The Pumps',
                'fr_FR' => 'Les Pumps',
            ],
        ]);

        $this->assertSame(201, $response);

        $category = $api->get('pumps');
        $this->assertSameContent([
            'code'   => 'pumps',
            'parent' => '2014_collection',
            'labels' => [
                'en_US' => 'The Pumps',
                'fr_FR' => 'Les Pumps',
            ],
        ], $category);
    }

    public function testCreateAnExistingCategory()
    {
        $api = $this->createClient()->getCategoryApi();

        try {
            $api->create('summer_collection', [
                'parent' => '2014_collection',
                'labels' => [
                    'en_US' => 'Summer collection',
                    'fr_FR' => 'Collection été',
                ],
            ]);
        } catch (UnprocessableEntityHttpException $exception) {
            $this->assertSame([
                [
                    'property' => 'code',
                    'message'  => 'This value is already used.',
                ],
            ], $exception->getResponseErrors());
        }
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function testCreateAnInvalidCategory()
    {
        $api = $this->createClient()->getCategoryApi();
        $api->create('fail', [
            'parent' => 'unknown parent',
            'labels' => [
                'en_US' => 'fail',
            ],
        ]);
    }
}
