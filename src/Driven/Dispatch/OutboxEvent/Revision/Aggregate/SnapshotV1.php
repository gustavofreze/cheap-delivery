<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Aggregate;

use CheapDelivery\Application\Domain\Models\Dispatch;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Snapshot;

final readonly class SnapshotV1 implements Snapshot
{
    public function __construct(private Dispatch $dispatch)
    {
    }

    public function toJson(): string
    {
        $shipment = $this->dispatch->shipment;
        $snapshot = [
            'id'       => $this->dispatch->id->getValue(),
            'shipment' => [
                'cost'        => $shipment?->cost->value,
                'carrierName' => $shipment?->carrierName->value
            ]
        ];

        return (string)json_encode($snapshot, JSON_PRESERVE_ZERO_FRACTION);
    }
}
