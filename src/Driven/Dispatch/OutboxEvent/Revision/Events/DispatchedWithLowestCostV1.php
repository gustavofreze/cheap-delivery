<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Events;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Aggregate\PayloadV1;
use CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Aggregate\SnapshotV1;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\AggregateType;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Revision;

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
            payload: new PayloadV1(event: $this->event),
            revision: new Revision(value: $this->event->revision()),
            snapshot: new SnapshotV1(dispatch: $aggregate),
            occurredOn: $this->event->instant,
            aggregateId: $aggregate->id,
            aggregateType: AggregateType::from(class: $aggregate::class)
        );
    }
}
