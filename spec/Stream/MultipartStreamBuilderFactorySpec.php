<?php

namespace spec\Akeneo\Pim\ApiClient\Stream;

use Akeneo\Pim\ApiClient\MultipartStream\MultipartStreamBuilder;
use Akeneo\Pim\ApiClient\Stream\MultipartStreamBuilderFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamFactoryInterface;

class MultipartStreamBuilderFactorySpec extends ObjectBehavior
{
    function let(StreamFactoryInterface $streamFactory)
    {
        $this->beConstructedWith($streamFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MultipartStreamBuilderFactory::class);
    }

    function it_creates_a_multipart_stream_builder()
    {
        $this
            ->create()
            ->shouldReturnAnInstanceOf(MultipartStreamBuilder::class);
    }
}
