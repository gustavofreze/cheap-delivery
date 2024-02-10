<?php

namespace CheapDelivery\Driven\Shipment\OutboxEvent;

use CheapDelivery\Application\Domain\Events\Event;
use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use CheapDelivery\Application\Domain\Models\Shipment;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\AggregateType;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Revision;
use CheapDelivery\Driven\Shipment\OutboxEvent\Schema\SnapshotV1;
use ReflectionClass;

final class EventRecordWrapper
{
    public function pack(Shipment $shipment): EventRecords
    {
        $eventRecords = new EventRecords();

        $name = (new ReflectionClass($shipment::class))->getShortName();
        $aggregateType = new AggregateType(value: $name);

        $shipment
            ->occurredEvents()
            ->each(fn(Event $event) => $eventRecords->add(
                item: $this->wrap(
                    event: $event,
                    identity: $shipment->id,
                    aggregateType: $aggregateType,
                    aggregateRoot: $shipment
                )
            ));

        return $eventRecords;
    }

    public function wrap(
        Event $event,
        Identity $identity,
        AggregateType $aggregateType,
        Shipment $aggregateRoot
    ): EventRecord {
        return new EventRecord(
            id: Uuid::generateV4(),
            type: $event->getType(),
            payload: $event,
            revision: new Revision(value: $event->getRevision()),
            snapshot: new SnapshotV1(shipment: $aggregateRoot),
            occurredOn: Utc::now(),
            aggregateId: $identity,
            aggregateType: $aggregateType
        );
    }
}
