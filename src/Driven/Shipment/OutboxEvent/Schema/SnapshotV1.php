<?php

namespace CheapDelivery\Driven\Shipment\OutboxEvent\Schema;

use CheapDelivery\Application\Domain\Models\Shipment;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Snapshot;

final readonly class SnapshotV1 implements Snapshot
{
    public function __construct(private Shipment $shipment)
    {
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->shipment->id->getValue(),
            'cost'        => $this->shipment->cost->value,
            'carrierName' => $this->shipment->carrierName->value
        ];
    }
}
