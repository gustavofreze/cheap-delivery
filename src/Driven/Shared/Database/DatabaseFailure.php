<?php

namespace CheapDelivery\Driven\Shared\Database;

interface DatabaseFailure
{
    /**
     * Get a string representation of the failure's trace.
     *
     * @return string The trace of the failure.
     */
    public function trace(): string;
}
