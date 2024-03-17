<?php

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Events;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Aggregate\SnapshotV1;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\AggregateType;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Revision;
use DateTimeImmutable;

final readonly class DispatchedWithLowestCostV1 implements DispatchEvent
{
    public function __construct(private DispatchedWithLowestCost $event)
    {
    }

    public function build(): EventRecord
    {
        $aggregate = $this->event->dispatch;

        return new EventRecord(
            id: Uuid::generateV4(),
            type: $this->event->type(),
            payload: $this->payload(),
            revision: new Revision(value: $this->event->revision()),
            snapshot: new SnapshotV1(dispatch: $aggregate),
            occurredOn: $this->event->instant,
            aggregateId: $aggregate->id,
            aggregateType: AggregateType::from(class: $aggregate::class)
        );
    }

    private function payload(): string
    {
        $dispatch = $this->event->dispatch;
        $shipment = $dispatch->shipment;
        $payload = [
            'id'       => $this->event->id->getValue(),
            'instant'  => $this->event->instant->dateTime->format(DateTimeImmutable::RFC3339),
            'dispatch' => [
                'id'       => $dispatch->id->getValue(),
                'shipment' => [
                    'cost'        => $shipment?->cost->value,
                    'carrierName' => $shipment?->carrierName->value
                ]
            ]
        ];

        return (string)json_encode($payload, JSON_PRESERVE_ZERO_FRACTION);
    }
}
