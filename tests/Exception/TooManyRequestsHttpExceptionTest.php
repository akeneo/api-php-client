<?php

namespace Akeneo\Pim\ApiClient\tests\Exception;

use Akeneo\Pim\ApiClient\Exception\RuntimeException;
use Akeneo\Pim\ApiClient\Exception\TooManyRequestsHttpException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TooManyRequestsHttpExceptionTest extends TestCase
{
    private RequestInterface $request;

    public function setUp(): void
    {
        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testRetryAfter(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->willReturn(true);
        $response->method('getHeader')->willReturn(['10']);

        $exception = new TooManyRequestsHttpException('Too Many Requests', $this->request, $response, null);

        $this->assertSame(
            10,
            $exception->getRetryAfter()
        );
    }

    public function testCannotFindRetryAfterHeader(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->willReturn(false);

        $exception = new TooManyRequestsHttpException('Too Many Requests', $this->request, $response, null);

        $this->expectException(RuntimeException::class);

        $exception->getRetryAfter();
    }

    public function testCannotParseRetryAfterHeader(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('hasHeader')->willReturn(true);
        $response->method('getHeader')->willReturn([0 => 'Wed, 21 Oct 2015 07:28:00 GMT']);

        $exception = new TooManyRequestsHttpException('Too Many Requests', $this->request, $response, null);

        $this->expectException(RuntimeException::class);

        $exception->getRetryAfter();
    }
}
