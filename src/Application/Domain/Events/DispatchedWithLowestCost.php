<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Events;

use CheapDelivery\Application\Domain\Events\Commons\DomainEventBehavior;
use CheapDelivery\Application\Domain\Models\Dispatch\Shipment;

final readonly class DispatchedWithLowestCost implements DispatchEvent
{
    use DomainEventBehavior;

    public function __construct(public Shipment $shipment)
    {
    }

    public function eventType(): string
    {
        return 'DispatchedWithLowestCost';
    }
}
