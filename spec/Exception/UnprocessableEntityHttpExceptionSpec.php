<?php

namespace spec\Akeneo\Pim\ApiClient\Exception;

use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class UnprocessableEntityHttpExceptionSpec extends ObjectBehavior
{
    function let(RequestInterface $request, ResponseInterface $response)
    {
        $this->beConstructedWith('message', $request, $response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UnprocessableEntityHttpException::class);
    }

    function it_exposes_the_response_errors($response, StreamInterface $body)
    {
        $response->getStatusCode()->willReturn(422);
        $response->getBody()->willReturn($body);
        $body->rewind()->shouldBeCalled();
        $body->getContents()->willReturn(
            <<<JSON
    {
        "code": "422",
        "message": "The response message",
        "errors": [
            {
                "property": "labels",
                "message": "The first error"
            },
            {
                "property": "labels",
                "message": "The second error"
            }
        ]
    }
JSON
        );

        $this->getResponseErrors()->shouldReturn([
            [
                'property' => 'labels',
                'message'  => 'The first error',
            ],
            [
                'property' => 'labels',
                'message'  => 'The second error',
            ]
        ]);
    }

    function it_returns_an_empty_array_when_the_response_has_no_errors($response, StreamInterface $body)
    {
        $response->getStatusCode()->willReturn(422);
        $response->getBody()->willReturn($body);
        $body->rewind()->shouldBeCalled();
        $body->getContents()->willReturn(
            <<<JSON
    {
        "code": "422",
    }
JSON
        );

        $this->getResponseErrors()->shouldReturn([]);
    }

    function it_returns_an_empty_array_when_the_body_is_not_a_valid_json($response, StreamInterface $body)
    {
        $response->getStatusCode()->willReturn(422);
        $response->getBody()->willReturn($body);
        $body->getContents()->willReturn('Not a json');
        $body->rewind()->shouldBeCalled();
        $this->getResponseErrors()->shouldReturn([]);
    }
}
