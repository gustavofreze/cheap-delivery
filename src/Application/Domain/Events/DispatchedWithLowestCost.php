<?php

namespace CheapDelivery\Application\Domain\Events;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Dispatch;

final readonly class DispatchedWithLowestCost implements Event
{
    use EventCapabilities;

    private const REVISION = 1;

    public function __construct(public Identity $id, public Dispatch $dispatch, public Utc $instant)
    {
    }

    public function revision(): int
    {
        return self::REVISION;
    }
}
