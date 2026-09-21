<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Outbox;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost as DispatchedFact;
use CheapDelivery\Application\Domain\Events\DispatchEvent;
use CheapDelivery\Driven\Dispatch\Outbox\Event\DispatchedWithLowestCost;
use TinyBlocks\BuildingBlocks\Event\EventRecord;
use TinyBlocks\BuildingBlocks\Event\IntegrationEvent;
use TinyBlocks\BuildingBlocks\Event\IntegrationEventTranslator;

final readonly class DispatchEventTranslator implements IntegrationEventTranslator
{
    public function supports(EventRecord $record): bool
    {
        return $record->event instanceof DispatchEvent;
    }

    public function translate(EventRecord $record): IntegrationEvent
    {
        /** @var DispatchedFact $event */
        $event = $record->event;

        return new DispatchedWithLowestCost(
            cost: $event->shipment->cost->toFloat(),
            carrierName: $event->shipment->carrierName->value
        );
    }
}
