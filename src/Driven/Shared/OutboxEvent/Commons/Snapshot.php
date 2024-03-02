<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

interface Snapshot
{
    /**
     * Converts the snapshot to a JSON string.
     *
     * @return string The JSON representation of the snapshot.
     */
    public function toJson(): string;
}
