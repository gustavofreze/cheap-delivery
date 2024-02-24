<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use Closure;
use Traversable;

trait Collection
{
    public function __construct(public iterable $items = [])
    {
        $this->items = $this->normalize(items: $items);
    }

    public function add(mixed $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    public function map(Closure $callback): static
    {
        return new static(array_map($callback, $this->items));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function each(Closure $callback): static
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    public function minBy(Closure $callback): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $sorted = clone $this;
        $sorted->sortByAsc(callback: fn(mixed $first, mixed $second) => ($callback($first) <=> $callback($second)));

        return $sorted->first();
    }

    public function filter(?Closure $callback = null): static
    {
        return new static(array_filter($this->items, $callback));
    }

    public function sortByAsc(Closure $callback): static
    {
        $sortedItems = $this->items;
        usort($sortedItems, fn(mixed $first, mixed $second) => $callback($first, $second));
        $this->items = $sortedItems;

        return $this;
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    private function normalize(iterable $items): array
    {
        return match (true) {
            $items instanceof Traversable => iterator_to_array($items),
            default => $items
        };
    }
}
