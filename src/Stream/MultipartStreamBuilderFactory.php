<?php

namespace Akeneo\Pim\ApiClient\Stream;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Message\StreamFactory;

/**
 * Factory to create a builder of Multipart streams
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MultipartStreamBuilderFactory
{
    /** @var StreamFactory */
    protected $streamFactory;

    /**
     * @param StreamFactory $streamFactory
     */
    public function __construct(StreamFactory $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return new MultipartStreamBuilder($this->streamFactory);
    }
}
