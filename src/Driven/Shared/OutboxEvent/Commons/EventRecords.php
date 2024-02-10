<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use CheapDelivery\Application\Domain\Models\Commons\Collectible;
use CheapDelivery\Application\Domain\Models\Commons\Collection;

final class EventRecords implements Collectible
{
    use Collection;
}
