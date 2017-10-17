<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class GetProductApiIntegration extends AbstractProductApiTestCase
{
    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getProductApi();

        $api->get('pumps');
    }
}
