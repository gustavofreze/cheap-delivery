<?php

namespace CheapDelivery\Application\Domain\Events;

interface Event
{
    /**
     * Gets the type of the event.
     *
     * @return string The event type.
     */
    public function getType(): string;

    /**
     * Gets the revision number of the event.
     *
     * @return int The event revision number.
     */
    public function getRevision(): int;
}
