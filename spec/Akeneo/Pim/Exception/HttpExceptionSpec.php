<?php

namespace spec\Akeneo\Pim\Exception;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpExceptionSpec extends ObjectBehavior
{
    function let(ResponseInterface $response)
    {
        $this->beConstructedWith('message', $response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Exception\HttpException');
    }

    function it_exposes_the_response_body($response, StreamInterface $body)
    {
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($body);
        $body->getContents()->willReturn('body content');
        $this->getResponseBody()->shouldReturn('body content');
    }

    function it_exposes_the_status_code_of_the_response($response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->getStatusCode()->shouldReturn(200);
    }

    function it_exposes_the_response($response)
    {
        $this->getResponse()->shouldReturn($response);
    }
}
