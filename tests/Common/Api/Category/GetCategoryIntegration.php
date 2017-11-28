<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Category;

use Akeneo\Pim\ApiClient\tests\Common\Api\ApiTestCase;

class GetCategoryIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getCategoryApi();

        $category = $api->get('sandals');

        $this->assertSameContent([
            'code'   => 'sandals',
            'parent' => 'summer_collection',
            'labels' => [
                'en_US' => 'Sandals',
                'fr_FR' => 'Sandales',
            ],
        ], $category);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getCategoryApi();

        $api->get('pumps');
    }
}
