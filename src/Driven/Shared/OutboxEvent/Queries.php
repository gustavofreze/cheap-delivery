<?php

namespace CheapDelivery\Driven\Shared\OutboxEvent;

final class Queries
{
    public const INSERT_EVENT = '
           INSERT INTO outbox_event (id, aggregate_type, aggregate_id, event_type, revision, payload, occurred_on)
           VALUES (:id, :aggregateType, :aggregateId, :eventType, :revision, :payload, :occurredOn);';
}
