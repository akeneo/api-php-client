<?php

namespace spec\Akeneo\Pim\MultipartStream;

use Akeneo\Pim\MultipartStream\MultipartStreamBuilderFactory;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Message\StreamFactory;
use PhpSpec\ObjectBehavior;

class MultipartStreamBuilderFactorySpec extends ObjectBehavior
{
    function let(StreamFactory $streamFactory)
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
