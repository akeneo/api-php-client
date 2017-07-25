<?php

namespace Akeneo\Pim\tests\Common\Api\Category;

use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class CreateCategoryIntegration extends ApiTestCase
{
    /**
     * @group common
     */
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

    /**
     * @group common
     */
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
     * @group common
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
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
