<?php

namespace CheapDelivery\Query;

interface Filters
{
    /**
     * Converts the filters to an associative array.
     *
     * @return array The filters as an associative array.
     */
    public function toArray(): array;
}
