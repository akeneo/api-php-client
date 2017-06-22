<?php

namespace Akeneo\Pim\tests\Api\Category;

use Akeneo\Pim\tests\Api\ApiTestCase;

class UpsertCategoryIntegration extends ApiTestCase
{
    public function testUpsertDoingUpdate()
    {
        $api = $this->createClient()->getCategoryApi();

        $response = $api->upsert('sandals', [
            'parent' => 'winter_collection',
            'labels' => [
                'en_US' => 'Sandals !',
                'fr_FR' => 'Sandales',
            ],
        ]);

        $this->assertSame(204, $response);

        $category = $api->get('sandals');
        $this->assertSameContent([
            'code'   => 'sandals',
            'parent' => 'winter_collection',
            'labels' => [
                'en_US' => 'Sandals !',
                'fr_FR' => 'Sandales',
            ],
        ], $category);
    }

    public function testUpsertDoingCreate()
    {
        $api = $this->createClient()->getCategoryApi();
        $response = $api->upsert('booties', [
            'parent' => 'summer_collection',
            'labels' => [
                'en_US' => 'Booties',
                'fr_FR' => 'Bottines',
            ],
        ]);

        $this->assertSame(201, $response);

        $category = $api->get('booties');
        $this->assertSameContent([
            'code'   => 'booties',
            'parent' => 'summer_collection',
            'labels' => [
                'en_US' => 'Booties',
                'fr_FR' => 'Bottines',
            ],
        ], $category);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertWrongDataTypeFail()
    {
        $api = $this->createClient()->getCategoryApi();
        $api->upsert('sandals', [
            'parent' => 'winter_collection',
            'labels' => [
                'en_US' => ['wrong data type'],
                'fr_FR' => 'Sandales',
            ],
        ]);
    }

    /**
     * @expectedException \Akeneo\Pim\Exception\UnprocessableEntityHttpException
     */
    public function testUpsertInvalidCodeFail()
    {
        $api = $this->createClient()->getCategoryApi();
        $api->upsert('invalid code !', [
            'parent' => 'winter_collection',
            'labels' => [
                'en_US' => 'Invalid code',
                'fr_FR' => 'Code invalide',
            ],
        ]);
    }
}
