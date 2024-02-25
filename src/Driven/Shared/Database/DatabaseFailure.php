<?php

namespace CheapDelivery\Driven\Shared\Database;

use Throwable;

interface DatabaseFailure extends Throwable
{
    /**
     * Get a string representation of the failure's trace.
     *
     * @return string The trace of the failure.
     */
    public function trace(): string;
}
