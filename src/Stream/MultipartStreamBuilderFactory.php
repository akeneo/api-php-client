<?php

namespace Akeneo\Pim\ApiClient\Stream;

use Akeneo\Pim\ApiClient\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Factory to create a builder of Multipart streams
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MultipartStreamBuilderFactory
{
    /** @var StreamFactoryInterface */
    protected $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    public function create(): MultipartStreamBuilder
    {
        return new MultipartStreamBuilder($this->streamFactory);
    }
}
