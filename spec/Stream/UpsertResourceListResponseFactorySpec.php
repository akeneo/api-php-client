<?php

namespace spec\Akeneo\Pim\ApiClient\Stream;

use Akeneo\Pim\ApiClient\Stream\LineStreamReader;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponse;
use Akeneo\Pim\ApiClient\Stream\UpsertResourceListResponseFactory;
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
