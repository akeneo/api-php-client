<?php

namespace Akeneo\Pim\tests\Common\Api\Product;

class DeleteProductApiIntegration extends AbstractProductApiTestCase
{
    /**
     * @group common
     */
    public function testDeleteSuccessful()
    {
        $api = $this->createClient()->getProductApi();
        $response = $api->delete('docks_white');

        $this->assertSame(204, $response);
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testDeleteOnAnUnknownProduct()
    {
        $api = $this->createClient()->getProductApi();
        $api->delete('unknown');
    }
}
