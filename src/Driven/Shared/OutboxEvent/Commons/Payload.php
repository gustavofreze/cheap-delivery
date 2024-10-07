<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

interface Payload
{
    /**
     * Converts the payload instance to its JSON representation.
     *
     * @return string The JSON-encoded string representation of the payload.
     */
    public function toJson(): string;
}
