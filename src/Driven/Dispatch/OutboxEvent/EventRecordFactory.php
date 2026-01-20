<?php

declare(strict_types=1);

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
        if ($event::class === DispatchedWithLowestCost::class) {
            return new DispatchedWithLowestCostV1(event: $event)->build();
        }

        $template = 'Event <%s> is not supported. Unable to create an EventRecord.';
        throw new LogicException(message: sprintf($template, $event::class));
    }
}
