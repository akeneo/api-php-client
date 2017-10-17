<?php

namespace Akeneo\Pim\tests\v2_0\Api\Currency;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetCurrencyApiIntegration extends ApiTestCase
{
    public function testGet()
    {
        $api = $this->createClient()->getCurrencyApi();

        $currency = $api->get('EUR');

        $this->assertSameContent([
            'code'    => 'EUR',
            'enabled' => true,
        ], $currency);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getCurrencyApi();

        $api->get('unknown');
    }
}
