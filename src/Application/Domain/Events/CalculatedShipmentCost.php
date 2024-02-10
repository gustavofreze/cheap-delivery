<?php

namespace CheapDelivery\Application\Domain\Events;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Shipment;

final readonly class CalculatedShipmentCost implements Event
{
    use EventCapabilities;

    private const REVISION = 1;

    public function __construct(public Identity $id, public Shipment $shipment, public Utc $instant)
    {
    }

    public function getRevision(): int
    {
        return self::REVISION;
    }
}
