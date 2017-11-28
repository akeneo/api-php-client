<?php

namespace Akeneo\Pim\ApiClient\tests\Common\Api\Product;

class GetProductApiIntegration extends AbstractProductApiTestCase
{
    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getProductApi();

        $api->get('pumps');
    }
}
