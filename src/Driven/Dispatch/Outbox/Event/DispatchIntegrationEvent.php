<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Outbox\Event;

use TinyBlocks\BuildingBlocks\Event\IntegrationEvent;

/**
 * Fact a dispatch publishes for consumers outside the delivery domain.
 */
interface DispatchIntegrationEvent extends IntegrationEvent
{
}
