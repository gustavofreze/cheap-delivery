<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use Closure;
use IteratorAggregate;

/**
 * @template TKey of mixed
 * @template TValue of mixed
 * @extends IteratorAggregate<int, mixed>
 */
interface Collectible extends IteratorAggregate
{
    /**
     * Adds an element to the collection.
     *
     * @param mixed $element The element to add.
     * @return Collectible<TKey, TValue> The modified collection.
     */
    public function add(mixed $element): Collectible;

    /**
     * Applies a callback to each element of the collection and returns a new collection with the results.
     *
     * @param Closure $callback The callback function to apply.
     * @return Collectible<TKey, TValue> A new collection with the mapped elements.
     */
    public function map(Closure $callback): Collectible;

    /**
     * Returns all elements of the collection.
     *
     * @return mixed[] The elements of the collection.
     */
    public function all(): array;

    /**
     * Iterates over each element of the collection and applies a callback function.
     *
     * @param Closure $callback The callback function to apply to each element.
     * @return Collectible<TKey, TValue> The modified collection.
     */
    public function each(Closure $callback): Collectible;

    /**
     * Returns the element of the collection with the minimum value according to a callback.
     *
     * @param Closure $callback The callback function to determine the value for comparison.
     * @return mixed|null The element with the minimum value, or null if the collection is empty.
     */
    public function minBy(Closure $callback): mixed;

    /**
     * Filters the elements of the collection using a callback function.
     *
     * @param Closure|null $callback The callback function to use for filtering.
     * @return Collectible<TKey, TValue> A new collection with the filtered elements.
     */
    public function filter(?Closure $callback = null): Collectible;

    /**
     * Returns the first element of the collection.
     *
     * @return mixed The first element of the collection.
     */
    public function first(): mixed;

    /**
     * Checks if the collection is empty.
     *
     * @return bool True if the collection is empty, false otherwise.
     */
    public function isEmpty(): bool;
}
