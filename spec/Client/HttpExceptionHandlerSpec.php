<?php

namespace spec\Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Exception\BadRequestHttpException;
use Akeneo\Pim\ApiClient\Exception\ClientErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\NotFoundHttpException;
use Akeneo\Pim\ApiClient\Exception\RedirectionHttpException;
use Akeneo\Pim\ApiClient\Exception\ServerErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\Client\HttpExceptionHandler;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpExceptionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(HttpExceptionHandler::class);
    }

    function it_throws_redirection_exception_when_status_code_3xx(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(301);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 301, "message": "Moved Permanently"}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new RedirectionHttpException(
                    'Moved Permanently',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_bad_request_exception_when_status_code_400(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(400);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 401, "message": "The request is invalid"}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new BadRequestHttpException(
                    'The request is invalid',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_unauthorized_request_exception_when_status_code_401(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(401);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 401, "message": "The access token provided has expired."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new UnauthorizedHttpException(
                    'The access token provided has expired.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_not_found_exception_when_status_code_404(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(404);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 404, "message": "Category \"noname\" does not exists."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new NotFoundHttpException(
                    'Category "noname" does not exists.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_bad_request_exception_when_status_code_422(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(422);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 422, "message": "Invalid data."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new UnprocessableEntityHttpException(
                    'Invalid data.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_bad_request_exception_when_status_code_4xx(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(405);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 405, "message": "Not allowed."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new ClientErrorHttpException(
                    'Not allowed.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_bad_request_exception_when_status_code_5xx(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(500);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 500, "message": "Internal error."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new ServerErrorHttpException(
                    'Internal error.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_returns_the_response_when_succesful_response(RequestInterface $request, ResponseInterface $response)
    {
        $response->getStatusCode()->willReturn(200);
        $this->transformResponseToException($request, $response)->shouldReturn($response);
    }

    function it_builds_exception_with_reason_phrase_when_response_has_no_message(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(400);
        $response->getReasonPhrase()->willReturn('Bad request exception');
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 400}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new BadRequestHttpException(
                    'Bad request exception',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_builds_exception_with_reason_phrase_when_response_is_not_a_valid_json(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(422);
        $response->getReasonPhrase()->willReturn('Unprocessable entity exception');
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('not json');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new UnprocessableEntityHttpException(
                    'Unprocessable entity exception',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }
}
