<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Events\Commons;

use TinyBlocks\BuildingBlocks\Event\DomainEvent as TinyBlocksDomainEvent;

/**
 * Domain event emitted by an aggregate root within the delivery domain.
 */
interface DomainEvent extends TinyBlocksDomainEvent
{
}
