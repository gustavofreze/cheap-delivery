<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent;

use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;

interface OutboxEvent
{
    /**
     * Pushes a collection of event records to the outbox.
     *
     * @param EventRecords $events The collection of event records to be pushed.
     */
    public function push(EventRecords $events): void;
}
