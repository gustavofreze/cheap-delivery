<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Dispatch\OutboxEvent\Revision\Aggregate;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Driven\Shared\OutboxEvent\Commons\Payload;
use DateTimeInterface;

final readonly class PayloadV1 implements Payload
{
    public function __construct(private DispatchedWithLowestCost $event)
    {
    }

    public function toJson(): string
    {
        $dispatch = $this->event->dispatch;
        $shipment = $dispatch->shipment;
        $payload = [
            'id'       => $this->event->id->getValue(),
            'instant'  => $this->event->instant->dateTime->format(DateTimeInterface::RFC3339),
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
