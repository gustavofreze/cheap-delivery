<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

interface Snapshot
{
    /**
     * Convert the snapshot to an array.
     *
     * @return array The snapshot as an array.
     */
    public function toArray(): array;
}
