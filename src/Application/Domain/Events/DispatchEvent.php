<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Events;

use CheapDelivery\Application\Domain\Events\Commons\DomainEvent;

/**
 * Fact recorded by the dispatch aggregate when it moves.
 */
interface DispatchEvent extends DomainEvent
{
}
