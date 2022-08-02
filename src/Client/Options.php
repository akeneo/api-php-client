<?php

declare(strict_types=1);

namespace Akeneo\Pim\ApiClient\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Options
{
    private array $options;

    private function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function fromArray(array $options): self
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'headers' => [],
        ]);
        $resolver->setAllowedTypes('headers', 'string[]');

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
}
