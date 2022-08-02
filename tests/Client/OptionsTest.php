<?php

namespace Akeneo\Pim\ApiClient\tests\Client;

use Akeneo\Pim\ApiClient\Client\Options;
use Akeneo\Pim\ApiClient\tests\Api\ApiTestCase;

class OptionsTest extends ApiTestCase
{
    public function testInvalidOption(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Options::fromArray(['unknown_option' => 'unknown_value']);
    }

    /**
     * @dataProvider validHeadersOptionProvider
     */
    public function testHeadersOption(array $inputOptions, array $expectedHeadersOption, bool $hasHeaders): void
    {
        $options = Options::fromArray($inputOptions);

        $this->assertSame($hasHeaders, $options->hasHeaders());
        $this->assertSame($expectedHeadersOption, $options->getHeaders());
    }

    public function validHeadersOptionProvider(): array
    {
        return [
            'empty array' => [
                [],
                [],
                false,
            ],
            'empty headers option' => [
                ['headers' => []],
                [],
                false,
            ],
            'filled headers option' => [
                ['headers' => ['X-HEADER' => 'content']],
                ['X-HEADER' => 'content'],
                true,
            ],
        ];
    }

    /**
     * @dataProvider invalidHeadersOptionProvider
     */
    public function testInvalidHeadersOption($invalidValue): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Options::fromArray(['headers' => $invalidValue]);
    }

    public function invalidHeadersOptionProvider(): array
    {
        return [
            [10],
            [10.45],
            [true],
            [new \StdClass()],
            [['X-HEADER' => 10]],
            [['X-HEADER' => 10.45]],
            [['X-HEADER' => true]],
            [['X-HEADER' => new \StdClass()]],
        ];
    }
}
