<?php

namespace spec\Akeneo\Pim\ApiClient\Routing;

use PhpSpec\ObjectBehavior;

class UriGeneratorSpec extends ObjectBehavior
{
    const BASE_URI = 'http://akeneo-pim.local/';

    function let()
    {
        $this->beConstructedWith(static::BASE_URI);
    }

    function it_generates_uri_without_having_parameters()
    {
        $this
            ->generate('/api')
            ->shouldReturn(static::BASE_URI.'api');
    }

    function it_generates_uri_having_uri_parameters()
    {
        $this
            ->generate('/api/%s/name/%s', ['foo', 'bar'])
            ->shouldReturn(static::BASE_URI.'api/foo/name/bar');
    }

    function it_generates_uri_having_uri_parameters_needing_encoding()
    {
        $this
            ->generate('/api/%s', ['na&? %me'])
            ->shouldReturn(static::BASE_URI.'api/na%26%3F%20%25me');
    }

    function it_generates_uri_having_uri_parameters_without_encoding_slashes()
    {
        $this
            ->generate('/api/%s', ['na/me'])
            ->shouldReturn(static::BASE_URI.'api/na/me');
    }

    function it_generates_uri_having_query_parameters()
    {
        $this
            ->generate('/api', [], ['limit' => 10, 'with_count' => true])
            ->shouldReturn(static::BASE_URI.'api?limit=10&with_count=true');
    }

    function it_generates_uri_with_boolean_as_string()
    {
        $this
            ->generate('/api', [], ['foo' => true, 'bar' => false])
            ->shouldReturn(static::BASE_URI.'api?foo=true&bar=false');
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
            ->generate('/api', [], $queryParameters)
            ->shouldReturn(static::BASE_URI.'api?test=%3Da%26&many%5B0%5D=%3F1%2F&many%5B1%5D=%5B2%5D');
    }

    function it_generates_uri_having_search_parameter_encoded_in_json()
    {
        $queryParameters = [
            'search' => ['categories' => [['operator' => 'IN', 'value' => 'master']]],
        ];

        $this
            ->generate('/api', [], $queryParameters)
            ->shouldReturn(static::BASE_URI.'api?search=%7B%22categories%22%3A%5B%7B%22operator%22%3A%22IN%22%2C%22value%22%3A%22master%22%7D%5D%7D');
    }
}
