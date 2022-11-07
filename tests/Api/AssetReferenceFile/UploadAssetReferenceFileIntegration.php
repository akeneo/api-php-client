<?php

namespace Akeneo\Pim\ApiClient\tests\v2_1\Api\AssetReferenceFile;

use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

class UploadAssetReferenceFileIntegration extends ApiTestCase
{
    public function test_upload_a_localizable_asset_reference_file()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/ziggy.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, 'ziggy', 'en_US'),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetReferenceFileApi();
        $responseCode = $api->uploadForLocalizableAsset($filePath, 'ziggy', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'], 'ziggy.png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 25685);

        Assert::assertSame(201, $responseCode);

        $assetReferenceFile = $api->getFromLocalizableAsset('ziggy', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($this->fakeUploadLocalizableInformations(), $assetReferenceFile);
    }

    public function test_upload_a_not_localizable_asset_reference_file()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/ziggy-certification.jpg');

        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI,
                'ziggy-certification',
                AssetReferenceFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadNotLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetReferenceFileApi();
        $responseCode = $api->uploadForNotLocalizableAsset($filePath, 'ziggy-certification');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame(
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'],
            'ziggy-certification.jpg'
        );
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/jpeg');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 10513);

        Assert::assertSame(201, $responseCode);

        $assetReferenceFile = $api->getFromNotLocalizableAsset('ziggy-certification');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');
        Assert::assertEquals($this->fakeUploadNotLocalizableInformations(), $assetReferenceFile);
    }

    public function test_upload_from_resource_file()
    {
        $filePath = __DIR__ . '/../../fixtures/ziggy.png';
        $file = fopen($filePath, 'rb');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, 'ziggy', 'en_US'),
            new ResponseStack(
                new Response(file_get_contents($filePath), [], 201),
                new Response(json_encode($this->fakeUploadLocalizableInformations()), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetReferenceFileApi();
        $responseCode = $api->uploadForLocalizableAsset($file, 'ziggy', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'POST');
        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name'], 'ziggy.png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type'], 'image/png');
        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size'], 25685);

        Assert::assertSame(201, $responseCode);

        $assetReferenceFile = $api->getFromLocalizableAsset('ziggy', 'en_US');

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
            '/' . sprintf(AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI, 'unknown_asset', 'en_US'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Not found"}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetReferenceFileApi();

        $api->uploadForLocalizableAsset($filePath, 'unknown_asset', 'en_US');
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\UploadAssetReferenceFileErrorException
     */
    public function test_upload_a_file_that_cannot_be_transformed_for_the_variations()
    {
        $filePath = realpath(__DIR__ . '/../../fixtures/unicorn.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetReferenceFileApi::ASSET_REFERENCE_FILE_URI,
                'unicorn',
                AssetReferenceFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response(json_encode($this->generateMessageForUploadAssetReferenceFileErrorException()))
            )
        );

        $api = $this->createClientByPassword()->getAssetReferenceFileApi();

        $api->uploadForNotLocalizableAsset($filePath, 'unicorn');
    }

    protected function fakeUploadLocalizableInformations()
    {
        return [
            'code' => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_ziggy.jpg',
            'locale' => 'en_US',
            '_link' => [
                'download' => [
                    'href' => '/api/rest/v1/assets/ziggy/reference-files/en_US/download'
                ]
            ],
        ];
    }

    protected function fakeUploadNotLocalizableInformations()
    {
        return [
            'code' => '5/c/8/3/5c835e7785cb174d8e7e39d7ee63be559f233be0_ziggy_certification.jpg',
            'locale' => null,
            '_link' => [
                'download' => [
                    'href' => '/api/rest/v1/assets/ziggy_certif/reference-files/no-locale/download'
                ]
            ],
        ];
    }

    protected function generateMessageForUploadAssetReferenceFileErrorException()
    {
        return [
            'message' => 'Some variation files were not generated properly.',
            'errors' => [
                [
                    'message' => 'Impossible to "resize" the image "/tmp/pim/file_storage/4/2/5/1/unicorn-en_US-ecommerce.png" with a width bigger than the original.',
                    'scope' => 'ecommerce',
                    'locale' => null
                ]
            ]
        ];
    }
}
