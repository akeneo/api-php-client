<?php

namespace spec\Akeneo\Pim\Routing;

use PhpSpec\ObjectBehavior;

/**
 * Class UriGeneratorSpec
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UriGeneratorSpec extends ObjectBehavior
{
    const BASE_URI = 'http://akeneo-pim.local/';

    function let()
    {
        $this->beConstructedWith(static::BASE_URI, [
            'simple_route' => '/api/rest/v1/attributes',
            'route_with_argument' => '/api/rest/v1/attributes/%s',
        ]);
    }

    function it_generates_uri_without_having_parameters()
    {
        $this
            ->generate('simple_route')
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes');
    }

    function it_generates_uri_having_uri_parameters()
    {
        $this
            ->generate('route_with_argument', ['name'])
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes/name');
    }

    function it_generates_uri_having_uri_parameters_needing_encoding()
    {
        $this
            ->generate('route_with_argument', ['na&? %me'])
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes/na%26%3F%20%25me');
    }

    function it_generates_uri_having_uri_parameters_without_encoding_slashes()
    {
        $this
            ->generate('route_with_argument', ['na/me'])
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes/na/me');
    }

    function it_generates_uri_having_query_parameters()
    {
        $this
            ->generate('simple_route', [], ['limit' => 10, 'with_count' => true])
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes?limit=10&with_count=1');
    }

    function it_generates_uri_having_query_parameters_needing_encoding()
    {
        $queryParameters = [
            'test' => '=a&',
            'many' => [
                '?1/',
                '[2]',
            ]
        ];

        $this
            ->generate('simple_route', [], $queryParameters)
            ->shouldReturn(static::BASE_URI.'api/rest/v1/attributes?test=%3Da%26&many%5B0%5D=%3F1%2F&many%5B1%5D=%5B2%5D');
    }

    function it_throws_exception_if_route_does_not_exists()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('generate', ['unknown_route']);
    }
}
