<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use Closure;
use Generator;
use Traversable;

/**
 * @implements Collectible<mixed, mixed>
 */
class Collection implements Collectible
{
    /**
     * @var mixed[]
     */
    private array $elements;

    /**
     * @param mixed[] $elements
     */
    public function __construct(iterable $elements = [])
    {
        $this->elements = $this->normalize(elements: $elements);
    }

    public function add(mixed $element): Collectible
    {
        $this->elements[] = $element;
        return $this;
    }

    public function map(Closure $callback): Collectible
    {
        return new self(array_map($callback, $this->elements));
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function each(Closure $callback): Collectible
    {
        foreach ($this->elements as $key => $element) {
            $callback($element, $key);
        }

        return $this;
    }

    public function minBy(Closure $callback): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        usort($this->elements, fn(mixed $first, mixed $second) => $callback($first) <=> $callback($second));

        return $this->first();
    }

    public function filter(?Closure $callback = null): Collectible
    {
        return new self(array_filter($this->elements, $callback));
    }

    public function first(): mixed
    {
        return reset($this->elements);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function getIterator(): Generator
    {
        foreach ($this->elements as $element) {
            yield $element;
        }
    }

    /**
     * @param mixed[] $elements
     * @return mixed[]
     */
    private function normalize(iterable $elements): array
    {
        return match (true) {
            $elements instanceof Traversable => iterator_to_array($elements),
            default => $elements
        };
    }
}
