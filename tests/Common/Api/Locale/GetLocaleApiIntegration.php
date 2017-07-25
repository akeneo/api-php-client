<?php

namespace Akeneo\Pim\tests\Common\Api\Locale;

use Akeneo\Pim\tests\Common\Api\ApiTestCase;

class GetLocaleApiIntegration extends ApiTestCase
{
    /**
     * @group common
     */
    public function testGet()
    {
        $api = $this->createClient()->getLocaleApi();

        $locale = $api->get('en_US');

        $this->assertSameContent([
            'code'    => 'en_US',
            'enabled' => true,
        ], $locale);
    }

    /**
     * @group common
     * @expectedException \Akeneo\Pim\Exception\NotFoundHttpException
     */
    public function testGetNotFound()
    {
        $api = $this->createClient()->getLocaleApi();

        $api->get('en_FR');
    }
}
