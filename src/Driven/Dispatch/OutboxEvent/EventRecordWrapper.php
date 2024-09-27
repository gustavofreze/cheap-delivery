<?php

namespace CheapDelivery\Driven\Dispatch\OutboxEvent;

use CheapDelivery\Application\Domain\Events\Event;
use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;
use TinyBlocks\Collection\Collectible;

final class EventRecordWrapper
{
    public function pack(Dispatch $dispatch): EventRecords|Collectible
    {
        $eventRecords = EventRecords::createFromEmpty();

        $dispatch
            ->occurredEvents()
            ->each(actions: fn(Event $event) => $eventRecords->add($this->wrap(event: $event)));

        return $eventRecords;
    }

    public function wrap(Event $event): EventRecord
    {
        return EventRecordFactory::from(event: $event);
    }
}
