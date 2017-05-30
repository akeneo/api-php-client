<?php

namespace spec\Akeneo\Pim\Stream;

use Akeneo\Pim\Stream\LineStreamReader;
use Akeneo\Pim\Stream\UpsertResourceListResponse;
use Akeneo\Pim\Stream\UpsertResourceListResponseFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamInterface;

class UpsertResourceListResponseFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UpsertResourceListResponseFactory::class);
    }

    public function it_creates_an_upsert_resource_list_response(StreamInterface $stream)
    {
        $this->create($stream)->shouldBeLike(
            new UpsertResourceListResponse($stream->getWrappedObject(), new LineStreamReader())
        );
    }
}
