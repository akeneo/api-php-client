<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Options
{
    private function __construct(
        private array $options,
    ) {
    }

    public static function fromArray(array $options): self
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'headers' => [],
            'retry' => false,
            'max-retry' => 5,
        ]);
        $resolver->setAllowedTypes('headers', 'string[]');
        $resolver->setAllowedTypes('retry', 'bool');
        $resolver->setAllowedTypes('max-retry', 'int');

        $options = $resolver->resolve($options);

        return new self($options);
    }

    public function hasHeaders(): bool
    {
        return [] !== $this->options['headers'];
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->options['headers'];
    }

    public function getMaxRetry(): int
    {
        return $this->options['max-retry'];
    }

    public function canRetry(): bool
    {
        return $this->options['retry'];
    }
}
