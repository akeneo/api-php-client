<?php

namespace spec\Akeneo\Pim\ApiClient\Stream;

use Akeneo\Pim\ApiClient\Stream\LineStreamReader;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamInterface;

class LineStreamReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LineStreamReader::class);
    }

    public function it_gets_next_line(StreamInterface $stream)
    {
        $stream->isReadable()->willReturn(true);

        $stream->eof()->willReturn(false, false, false, false, false, false, true);
        $stream->read(1)->willReturn('{', '}', PHP_EOL, '{', 'a', '}');

        $this->getNextLine($stream)->shouldReturn('{}');
    }

    public function it_gets_last_line(StreamInterface $stream)
    {
        $stream->isReadable()->willReturn(true);

        $stream->eof()->willReturn(false, false, false, false, true);
        $stream->read(1)->willReturn('{', 'a', '}');

        $this->getNextLine($stream)->shouldReturn('{a}');
    }

    public function it_returns_null_if_stream_is_not_readable(StreamInterface $stream)
    {
        $stream->isReadable()->willReturn(false);

        $this->getNextLine($stream)->shouldReturn(null);
    }

    public function it_returns_null_if_stream_is_at_the_end(StreamInterface $stream)
    {
        $stream->isReadable()->willReturn(true);
        $stream->eof()->willReturn(true);

        $this->getNextLine($stream)->shouldReturn(null);
    }
}
