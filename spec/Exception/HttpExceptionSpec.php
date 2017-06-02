<?php

namespace spec\Akeneo\Pim\Exception;

use Akeneo\Pim\Exception\HttpException;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpExceptionSpec extends ObjectBehavior
{
    function let(RequestInterface $request, ResponseInterface $response)
    {
        $this->beConstructedWith('message', $request, $response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HttpException::class);
    }

    function it_exposes_the_status_code_of_the_response($response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->getCode()->shouldReturn(200);
    }

    function it_exposes_the_response($response)
    {
        $this->getResponse()->shouldReturn($response);
    }

    function it_exposes_the_request($request)
    {
        $this->getRequest()->shouldReturn($request);
    }
}
