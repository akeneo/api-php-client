<?php

namespace spec\Akeneo\Pim\ApiClient\Client;

use Akeneo\Pim\ApiClient\Client\HttpExceptionHandler;
use Akeneo\Pim\ApiClient\Exception\BadRequestHttpException;
use Akeneo\Pim\ApiClient\Exception\ClientErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\ForbiddenHttpException;
use Akeneo\Pim\ApiClient\Exception\MethodNotAllowedHttpException;
use Akeneo\Pim\ApiClient\Exception\NotAcceptableHttpException;
use Akeneo\Pim\ApiClient\Exception\NotFoundHttpException;
use Akeneo\Pim\ApiClient\Exception\RedirectionHttpException;
use Akeneo\Pim\ApiClient\Exception\ServerErrorHttpException;
use Akeneo\Pim\ApiClient\Exception\TooManyRequestsHttpException;
use Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Akeneo\Pim\ApiClient\Exception\UnsupportedMediaTypeHttpException;
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

    function it_throws_forbidden_exception_when_status_code_403(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(403);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 403, "message": "Access forbidden."}');
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new ForbiddenHttpException(
                    'Access forbidden.',
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

    function it_throws_method_not_allowed_exception_when_status_code_405(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(405);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn(<<<JSON
            {
                "code": 405,
                "message": "No route found for 'POST /api/rest/v1/products/myproduct': Method Not Allowed (Allow: GET, PATCH, DELETE)"
            }
        JSON);
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new MethodNotAllowedHttpException(
                    'No route found for \'POST /api/rest/v1/products/myproduct\': Method Not Allowed (Allow: GET, PATCH, DELETE)',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_method_not_allowed_exception_when_status_code_406(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(406);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn(<<<JSON
            {
                "code": 406,
                "message": "‘xxx’ in ‘Accept‘ header is not valid. Only ‘application/json‘ is allowed."
            }
        JSON);
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new NotAcceptableHttpException(
                    '‘xxx’ in ‘Accept‘ header is not valid. Only ‘application/json‘ is allowed.',
                    $request->getWrappedObject(),
                    $response->getWrappedObject()
                )
            )
            ->during('transformResponseToException', [$request, $response]);
    }

    function it_throws_method_not_allowed_exception_when_status_code_415(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(415);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn(<<<JSON
            {
                "code": 415,
                "message": "The ‘Content-type’ header is missing. ‘application/json’ has to specified as value."
            }
        JSON);
        $responseBody->rewind()->shouldBeCalled();
        $this
            ->shouldThrow(
                new UnsupportedMediaTypeHttpException(
                    'The ‘Content-type’ header is missing. ‘application/json’ has to specified as value.',
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

    function it_throws_bad_request_exception_when_status_code_429(
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $responseBody
    ) {
        $response->getStatusCode()->willReturn(429);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('Too Many Requests');
        $responseBody->getContents()->shouldBeCalled();
        $this
            ->shouldThrow(
                new TooManyRequestsHttpException(
                    'Too Many Requests',
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
        $response->getStatusCode()->willReturn(418);
        $response->getBody()->willReturn($responseBody);
        $responseBody->getContents()->willReturn('{"code": 418, "message": "Not allowed."}');
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
