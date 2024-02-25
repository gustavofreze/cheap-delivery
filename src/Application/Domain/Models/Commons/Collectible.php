<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use Closure;

interface Collectible
{
    /**
     * Adds an item to the collection.
     *
     * @param mixed $item The item to add.
     * @return Collectible
     */
    public function add(mixed $item): Collectible;

    /**
     * Applies a callback to each item in the collection.
     *
     * @param Closure $callback The callback function.
     * @return Collectible
     */
    public function map(Closure $callback): Collectible;

    /**
     * Get all the items in the collection.
     *
     * @return mixed[]
     */
    public function all(): array;

    /**
     * Applies a callback to each item in the collection.
     *
     * @param Closure $callback The callback function.
     * @return Collectible
     */
    public function each(Closure $callback): Collectible;

    /**
     * Finds the minimum item in the collection based on a callback.
     *
     * @param Closure $callback The callback function.
     * @return mixed|null
     */
    public function minBy(Closure $callback): mixed;

    /**
     * Filters the collection based on a callback.
     *
     * @param Closure|null $callback The callback function.
     * @return Collectible
     */
    public function filter(?Closure $callback = null): Collectible;

    /**
     * Sorts the collection in ascending order based on a callback.
     *
     * @param Closure $callback The callback function.
     * @return Collectible
     */
    public function sortByAsc(Closure $callback): Collectible;

    /**
     * Gets the first item in the collection.
     *
     * @return mixed
     */
    public function first(): mixed;

    /**
     * Checks if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}
