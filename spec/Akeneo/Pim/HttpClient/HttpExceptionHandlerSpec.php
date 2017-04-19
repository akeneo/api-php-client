<?php

namespace spec\Akeneo\Pim\HttpClient;

use Akeneo\Pim\Exception\BadRequestHttpException;
use Akeneo\Pim\Exception\ClientErrorHttpException;
use Akeneo\Pim\Exception\ServerErrorHttpException;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

class HttpExceptionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\HttpClient\HttpExceptionHandler');
    }

    function it_throws_bad_request_exception_when_status_code_400(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(400);
        $response->getReasonPhrase()->willReturn('Bad request exception');
        $this
            ->shouldThrow(
                new BadRequestHttpException('Bad request exception', $response->getWrappedObject())
            )
            ->during('transformResponseToException', [$response]);
    }

    function it_throws_unauthorized_request_exception_when_status_code_401(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(401);
        $response->getReasonPhrase()->willReturn('Unauthorized request exception');
        $this
            ->shouldThrow(
                new UnauthorizedHttpException('Unauthorized request exception', $response->getWrappedObject())
            )
            ->during('transformResponseToException', [$response]);
    }

    function it_throws_unprocessable_entity_exception_when_status_code_422(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(422);
        $response->getReasonPhrase()->willReturn('Unprocessable entity exception');
        $this
            ->shouldThrow(
                new UnprocessableEntityHttpException('Unprocessable entity exception', $response->getWrappedObject())
            )
            ->during('transformResponseToException', [$response]);
    }

    function it_throws_client_error_exception_when_status_code_4xx(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(404);
        $response->getReasonPhrase()->willReturn('Not found');
        $this
            ->shouldThrow(
                new ClientErrorHttpException('Not found', $response->getWrappedObject())
            )
            ->during('transformResponseToException', [$response]);
    }

    function it_throws_client_error_exception_when_status_code_5xx(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(500);
        $response->getReasonPhrase()->willReturn('Server error');
        $this
            ->shouldThrow(
                new ServerErrorHttpException('Server error', $response->getWrappedObject())
            )
            ->during('transformResponseToException', [$response]);
    }

    function it_returns_the_response_when_succesful_response(ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->transformResponseToException($response, [200])->shouldReturn($response);
    }
}
