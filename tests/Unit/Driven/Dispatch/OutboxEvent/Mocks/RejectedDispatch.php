<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Mocks;

use CheapDelivery\Application\Domain\Events\Event;
use CheapDelivery\Application\Domain\Events\EventCapabilities;

final class RejectedDispatch implements Event
{
    use EventCapabilities;

    public function revision(): int
    {
        return 1;
    }
}
