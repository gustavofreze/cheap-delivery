<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Outbox\Event;

use TinyBlocks\BuildingBlocks\Event\IntegrationEventBehavior;

final readonly class DispatchedWithLowestCost implements DispatchIntegrationEvent
{
    use IntegrationEventBehavior;

    public function __construct(public float $cost, public string $carrierName)
    {
    }
}
