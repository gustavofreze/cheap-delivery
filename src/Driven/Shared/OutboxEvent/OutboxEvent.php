<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent;

use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;
use TinyBlocks\Collection\Collectible;

interface OutboxEvent
{
    /**
     * Pushes a collection of event records to the outbox.
     *
     * @param EventRecords|Collectible $events The collection of event records to be pushed.
     */
    public function push(EventRecords|Collectible $events): void;
}
