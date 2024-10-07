<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Shared\OutboxEvent\Commons;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;

final readonly class EventRecord
{
    public function __construct(
        public Uuid $id,
        public string $type,
        public Payload $payload,
        public Revision $revision,
        public Snapshot $snapshot,
        public Utc $occurredOn,
        public Identity $aggregateId,
        public AggregateType $aggregateType
    ) {
    }
}
