<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\tests\Api\ReferenceEntityMediaFile;

use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApi;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;
use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseStack;
use PHPUnit\Framework\Assert;

/**
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CreateReferenceEntityMediaFileIntegration extends ApiTestCase
{
    public function test_create_media_file()
    {
        $this->server->setResponseOfPath(
            '/' . ReferenceEntityMediaFileApi::MEDIA_FILE_CREATE_URI,
            new ResponseStack(
                new Response('', ['Reference-entities-media-file-code' => 'my-media-code'], 201)
            )
        );
        $mediaFile = realpath(__DIR__ . '/../../fixtures/unicorn.png');
        $response = $this->createClientByPassword()->getReferenceEntityMediaFileApi()->create($mediaFile);

        Assert::assertNotEmpty($this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']);
        Assert::assertSame(
            'unicorn.png',
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['name']
        );
        Assert::assertSame(
            'image/png',
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['type']
        );
        Assert::assertSame(
            11255,
            $this->server->getLastRequest()->jsonSerialize()[RequestInfo::JSON_KEY_FILES]['file']['size']
        );
        Assert::assertSame('my-media-code', $response);
    }

    public function test_get_media_file_code_regardless_of_the_header_case()
    {
        $this->server->setResponseOfPath(
            '/' . ReferenceEntityMediaFileApi::MEDIA_FILE_CREATE_URI,
            new ResponseStack(
                new Response('', ['Reference-Entities-Media-File-Code' => 'my-media-code'], 201)
            )
        );
        $mediaFile = realpath(__DIR__ . '/../../fixtures/unicorn.png');
        $response = $this->createClientByPassword()->getReferenceEntityMediaFileApi()->create($mediaFile);

        Assert::assertSame('my-media-code', $response);
    }
}
