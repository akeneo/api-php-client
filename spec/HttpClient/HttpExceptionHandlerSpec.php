<?php

namespace spec\Akeneo\Pim\HttpClient;

use Akeneo\Pim\Exception\BadRequestHttpException;
use Akeneo\Pim\Exception\ClientErrorHttpException;
use Akeneo\Pim\Exception\NotFoundHttpException;
use Akeneo\Pim\Exception\ServerErrorHttpException;
use Akeneo\Pim\Exception\UnauthorizedHttpException;
use Akeneo\Pim\Exception\UnprocessableEntityHttpException;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpExceptionHandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\HttpClient\HttpExceptionHandler');
    }

    function it_throws_bad_request_exception_when_status_code_400(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(400);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 401, "message": "The request is invalid"}');
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
