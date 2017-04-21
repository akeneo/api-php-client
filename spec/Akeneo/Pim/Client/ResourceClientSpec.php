<?php

namespace spec\Akeneo\Pim\Client;

use Akeneo\Pim\HttpClient\HttpClient;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResourceClientSpec extends ObjectBehavior
{
    function let(HttpClient $httpClient)
    {
        $this->beConstructedWith($httpClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Akeneo\Pim\Client\ResourceClientInterface');
    }

    function it_get_resource($httpClient, ResponseInterface $response, StreamInterface $responseBody)
    {
        $uri = 'http://localhost';
        $headers = ['Content-Type' => 'application/json'];
        $resource =
<<<JSON
{
    "code": "winter_collection",
    "parent": null,
    "labels": {
        "en_US": "Winter collection",
        "fr_FR": "Collection hiver"
    }
}
JSON;

        $httpClient
            ->sendRequest('GET', $uri, $headers)
            ->shouldBeCalled()
            ->willReturn($response);

        $response
            ->getBody()
            ->shouldBeCalled()
            ->willReturn($responseBody);

        $responseBody
            ->getContents()
            ->shouldBeCalled()
            ->willReturn($resource);

        $this->getResource($uri, $headers)->shouldReturn([
            'code' => 'winter_collection',
            'parent' => null,
            'labels' => [
                'en_US' => 'Winter collection',
                'fr_FR' => 'Collection hiver',
            ],
        ]);
    }
}
