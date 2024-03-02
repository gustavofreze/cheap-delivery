<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent;

final class Queries
{
    public const INSERT_EVENT = '
                 INSERT INTO outbox_event (id, aggregate_type, aggregate_id, event_type, revision, snapshot,
                                           payload, occurred_on)
                 VALUES (UUID_TO_BIN(:id), :aggregateType, :aggregateId, :eventType, :revision, :snapshot,
                         :payload, :occurredOn);';
}
