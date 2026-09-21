<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\Outbox;

use CheapDelivery\Driven\Dispatch\Outbox\Event\DispatchIntegrationEvent;
use TinyBlocks\BuildingBlocks\Event\IntegrationEventRecord;
use TinyBlocks\Mapper\Serializer;
use TinyBlocks\Outbox\Serialization\PayloadSerializer;
use TinyBlocks\Outbox\Serialization\SerializedPayload;

final readonly class DispatchEventPayloadSerializer implements PayloadSerializer
{
    public function __construct(private Serializer $mapper)
    {
    }

    public function supports(IntegrationEventRecord $record): bool
    {
        return $record->event instanceof DispatchIntegrationEvent;
    }

    public function serialize(IntegrationEventRecord $record): SerializedPayload
    {
        return SerializedPayload::fromArray(payload: $this->mapper->toArray(source: $record->event));
    }
}
