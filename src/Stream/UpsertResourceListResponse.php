<?php

namespace Akeneo\Pim\ApiClient\Stream;

use Psr\Http\Message\StreamInterface;

/**
 * Iterator to read the response returned by an upsert of a list of resources.
 *
 * It iterates over the response body line by line.
 * Each line represents the JSON response of an upserted resource.
 *
 * It automatically decodes the JSON before returning the line.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UpsertResourceListResponse implements \Iterator
{
    /** @var int */
    protected $lineNumber = 1;

    /** @var string */
    protected $line;

    public function __construct(
        protected StreamInterface $bodyStream,
        protected LineStreamReader $streamReader
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function current(): array
    {
        return json_decode($this->line, true);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->line = $this->streamReader->getNextLine($this->bodyStream);
        $this->lineNumber++;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): int
    {
        return $this->lineNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return null !== $this->line;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->bodyStream->rewind();
        $this->lineNumber = 1;
        $this->line = $this->streamReader->getNextLine($this->bodyStream);
    }
}
