<?php

namespace CheapDelivery\Driven\Dispatch\OutboxEvent;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Events\Event;
use CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Events\DispatchedWithLowestCostV1;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use LogicException;

final readonly class EventRecordFactory
{
    public static function from(Event $event): EventRecord
    {
        $record = match (get_class($event)) {
            DispatchedWithLowestCost::class => new DispatchedWithLowestCostV1(event: $event),
            default => throw new LogicException(message: 'Unsupported event type. Cannot convert event to EventRecord.')
        };

        return $record->build();
    }
}
