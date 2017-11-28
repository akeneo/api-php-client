<?php

namespace spec\Akeneo\Pim\ApiClient\Stream;

use Akeneo\Pim\ApiClient\Stream\LineStreamReader;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamInterface;

class UpsertResourceListResponseSpec extends ObjectBehavior
{
    function let(StreamInterface $stream, LineStreamReader $streamReader)
    {
        $this->beConstructedWith($stream, $streamReader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UpsertResourceListResponse::class);
        $this->shouldImplement(\Traversable::class);
    }

    function it_returns_the_list_of_responses_line_by_line_when_iterating($stream, $streamReader)
    {

        $lines = [
            '{"line":1,"code":"code_1","status_code":204}',
            '{"line":2,"code":"code_2","status_code":422,"message":"error message"}',
            '{"line":3,"code":"code_3","status_code":201}',
        ];

        // methods that not iterate can be called twice
        $stream->rewind()->shouldBeCalled();
        $streamReader->getNextLine($stream)->willReturn($lines[0]);

        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['line' => 1, 'code' => 'code_1', 'status_code' => 204]);
        $this->current()->shouldReturn(['line' => 1, 'code' => 'code_1', 'status_code' => 204]);
        $this->key()->shouldReturn(1);
        $this->key()->shouldReturn(1);

        // for each call sequence
        $stream->rewind()->shouldBeCalled();
        $streamReader->getNextLine($stream)->willReturn($lines[0], $lines[1], $lines[2], null, $lines[0]);

        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['line' => 1, 'code' => 'code_1', 'status_code' => 204]);
        $this->key()->shouldReturn(1);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['line' => 2, 'code' => 'code_2', 'status_code' => 422, 'message' => 'error message']);
        $this->key()->shouldReturn(2);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['line' => 3, 'code' => 'code_3', 'status_code' => 201]);
        $this->key()->shouldReturn(3);

        $this->next()->shouldReturn(null);
        $this->valid()->shouldReturn(false);

        // check that rewind is working
        $this->rewind()->shouldReturn(null);
        $this->valid()->shouldReturn(true);
        $this->current()->shouldReturn(['line' => 1, 'code' => 'code_1', 'status_code' => 204]);
        $this->key()->shouldReturn(1);
    }
}
