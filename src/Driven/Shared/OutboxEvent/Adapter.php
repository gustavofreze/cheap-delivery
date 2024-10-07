<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent;

use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecord;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;
use DateTimeInterface;

final readonly class Adapter implements OutboxEvent
{
    public function __construct(private RelationalConnection $connection)
    {
    }

    public function push(EventRecords $events): void
    {
        $events->each(actions: fn(EventRecord $record) => $this->connection
            ->with()
            ->query(sql: Queries::INSERT_EVENT)
            ->bind(data: [
                ':id'            => $record->id->toString(),
                ':payload'       => $record->payload->toJson(),
                ':snapshot'      => $record->snapshot->toJson(),
                ':revision'      => $record->revision->value,
                ':eventType'     => $record->type,
                ':occurredOn'    => $record->occurredOn->dateTime->format(DateTimeInterface::RFC3339),
                ':aggregateId'   => $record->aggregateId->getValue(),
                ':aggregateType' => $record->aggregateType->value
            ])
            ->execute());
    }
}
