<?php

namespace CheapDelivery\Driven\Dispatch\OutboxEvent;

use CheapDelivery\Application\Domain\Events\Event;
use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;

final class EventRecordWrapper
{
    public function pack(Dispatch $dispatch): EventRecords
    {
        $eventRecords = new EventRecords();

        $dispatch
            ->occurredEvents()
            ->each(callback: fn(Event $event) => $eventRecords->add(element: $this->wrap(event: $event)));

        return $eventRecords;
    }

    public function wrap(Event $event): EventRecord
    {
        return EventRecordFactory::from(event: $event);
    }
}
