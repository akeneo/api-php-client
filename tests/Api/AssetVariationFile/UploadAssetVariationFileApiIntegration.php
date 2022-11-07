<?php

namespace Akeneo\Pim\ApiClient\tests\v2_1\Api\AssetVariationFile;

use Akeneo\Pim\ApiClient\Api\AssetVariationFileApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UploadAssetVariationFileApiIntegration extends ApiTestCase
{
    public function test_upload_a_localizable_asset_variation_file()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/ziggy.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, 'ziggy', 'ecommerce', 'en_US'),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $responseCode = $api->uploadForLocalizableAsset($filePath, 'ziggy', 'ecommerce', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'], 'ziggy.png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 25685);

        Assert::assertSame(201, $responseCode);

        $assetVariationFile = $api->getFromLocalizableAsset('ziggy', 'ecommerce', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($this->fakeUploadLocalizableInformations(), $assetVariationFile);
    }


    public function test_upload_a_not_localizable_asset_variation_file()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/ziggy-certification.jpg');

        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetVariationFileApi::ASSET_VARIATION_FILE_URI,
                'ziggy_certif',
                'ecommerce',
                AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadNotLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $responseCode = $api->uploadForNotLocalizableAsset($filePath, 'ziggy_certif', 'ecommerce');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'],
            'ziggy-certification.jpg'
        );
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/jpeg');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 10513);

        Assert::assertSame(201, $responseCode);

        $assetVariationFile = $api->getFromNotLocalizableAsset('ziggy_certif', 'ecommerce');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($this->fakeUploadNotLocalizableInformations(), $assetVariationFile);
    }

    public function test_upload_from_resource_file()
    {
        $filePath = __DIR__ . '/../../fixtures/ziggy.png';
        $file = fopen($filePath, 'rb');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, 'ziggy', 'ecommerce', 'en_US'),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $responseCode = $api->uploadForLocalizableAsset($file, 'ziggy', 'ecommerce', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'], 'ziggy.png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 25685);

        Assert::assertSame(201, $responseCode);

        $assetReferenceFile = $api->getFromLocalizableAsset('ziggy', 'ecommerce', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($this->fakeUploadLocalizableInformations(), $assetReferenceFile);
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function test_upload_for_an_unknown_asset()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/ziggy.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetVariationFileApi::ASSET_VARIATION_FILE_URI, 'unknown_asset', 'ecommerce', 'en_US'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Not found"}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();

        $api->uploadForLocalizableAsset($filePath, 'unknown_asset', 'ecommerce', 'en_US');
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException
     */
    public function test_upload_for_an_asset_that_should_be_localizable()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/unicorn.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetVariationFileApi::ASSET_VARIATION_FILE_URI,
                'unicorn',
                'ecommerce',
                AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response('{"code": 422, "message":"Unprocessable Entity"}', [], 422)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();

        $api->uploadForNotLocalizableAsset($filePath, 'unicorn', 'ecommerce');
    }

    protected function fakeUploadLocalizableInformations()
    {
        return [
            'code' => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_ziggy_ecommerce.jpg',
            'locale' => 'en_US',
            '_link' => [
                'download' => [
                    'href' => '/api/rest/v1/assets/ziggy/variation-files/ecommerce/en_US/download'
                ]
            ]
        ];
    }

    protected function fakeUploadNotLocalizableInformations()
    {
        return [
            'code' => '2/9/b/f/29bfa18ced500c5fca2072dab978737576ca47ca_ziggy_certification_ecommerce.jpg',
            'locale' => null,
            '_link' => [
                'download' => [
                    'href' => '/api/rest/v1/assets/ziggy_certif/variation-files/ecommerce/no-locale/download'
                ]
            ]
        ];
    }
}
