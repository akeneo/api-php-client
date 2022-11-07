<?php

namespace Akeneo\Pim\ApiClient\tests\v2_1\Api\AssetVariationFile;

use Akeneo\Pim\ApiClient\Api\AssetVariationFileApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class DownloadAssetVariationFileApiIntegration extends ApiTestCase
{
    public function test_download_a_localizable_asset_variation_file()
    {
        $expectedFilePath = realpath(__DIR__ . '/../../fixtures/ziggy.png');

        $this->server->setResponseOfPath(
            '/' . sprintf(AssetVariationFileApi::ASSET_VARIATION_FILE_DOWNLOAD_URI, 'ziggy', 'ecommerce', 'en_US'),
            new ResponseStack(
                new Response(file_get_contents($expectedFilePath), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $downloadResponse = $api->downloadFromLocalizableAsset('ziggy', 'ecommerce', 'en_US');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');

        Assert::assertInstanceOf(ResponseInterface::class, $downloadResponse);
        Assert::assertSame(file_get_contents($expectedFilePath), $downloadResponse->getBody()->getContents());
    }


    public function test_download_a_not_localizable_asset_variation_file()
    {
        $expectedFilePath = realpath(__DIR__ . '/../../fixtures/ziggy-certification.jpg');

        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetVariationFileApi::ASSET_VARIATION_FILE_DOWNLOAD_URI,
                'ziggy_certif',
                'ecommerce',
                AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response(file_get_contents($expectedFilePath), [], 201)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $downloadResponse = $api->downloadFromNotLocalizableAsset('ziggy_certif', 'ecommerce');

        Assert::assertSame($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_METHOD], 'GET');

        Assert::assertInstanceOf(ResponseInterface::class, $downloadResponse);
        Assert::assertSame(file_get_contents($expectedFilePath), $downloadResponse->getBody()->getContents());
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function test_download_from_localizable_asset_not_found()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(AssetVariationFileApi::ASSET_VARIATION_FILE_DOWNLOAD_URI, 'ziggy', 'mobile', 'en_US'),
            new ResponseStack(
                new Response('{"code": 404, "message":"Not found"}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $api->downloadFromLocalizableAsset('ziggy', 'mobile', 'en_US');
    }

    /**
     * @expectedException \Akeneo\Pim\ApiClient\Exception\NotFoundHttpException
     */
    public function test_download_from_not_localizable_asset_not_found()
    {
        $this->server->setResponseOfPath(
            '/' . sprintf(
                AssetVariationFileApi::ASSET_VARIATION_FILE_DOWNLOAD_URI,
                'ziggy_certif',
                'mobile',
                AssetVariationFileApi::NOT_LOCALIZABLE_ASSET_LOCALE_CODE
            ),
            new ResponseStack(
                new Response('{"code": 404, "message":"Not found"}', [], 404)
            )
        );

        $api = $this->createClientByPassword()->getAssetVariationFileApi();
        $api->downloadFromNotLocalizableAsset('ziggy_certif', 'mobile');
    }
}
