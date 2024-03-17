<?php

namespace CheapDelivery\Query;

interface Filters
{
    /**
     * Creates a Filters instance from an array of data.
     *
     * @param mixed[] $data The data to create the Filters instance from.
     * @return Filters The created Filters instance.
     */
    public static function from(array $data): Filters;

    /**
     * Converts the filters to an associative array.
     *
     * @return mixed[] The filters as an associative array.
     */
    public function toArray(): array;
}
