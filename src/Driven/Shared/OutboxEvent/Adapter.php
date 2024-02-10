<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent;

use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\EventRecords;

final readonly class Adapter implements OutboxEvent
{
    public function __construct(private RelationalConnection $connection)
    {
    }

    public function push(EventRecords $events): void
    {
        $events->each(fn() => $this->connection->with()->query(sql: Queries::INSERT_EVENT)->execute());
    }
}
