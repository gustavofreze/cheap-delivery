<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Events;

/**
 * Represents an event in the domain.
 */
interface Event
{
    /**
     * Gets the type of the event.
     *
     * @return string The event type.
     */
    public function type(): string;

    /**
     * Gets the revision of the event.
     *
     * @return int The event revision.
     */
    public function revision(): int;
}
