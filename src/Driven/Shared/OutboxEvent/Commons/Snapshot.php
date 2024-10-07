<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

interface Snapshot
{
    /**
     * Converts the snapshot instance to its JSON representation.
     *
     * @return string The JSON-encoded string representation of the snapshot.
     */
    public function toJson(): string;
}
