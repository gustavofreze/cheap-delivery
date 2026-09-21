<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\Shared\ReadModel;

final readonly class Dispatch
{
    private function __construct(
        public string $id,
        public float $cost,
        public string $createdAt,
        public string $carrierName
    ) {
    }

    public static function from(string $id, float $cost, string $createdAt, string $carrierName): Dispatch
    {
        return new Dispatch(id: $id, cost: $cost, createdAt: $createdAt, carrierName: $carrierName);
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'cost'         => $this->cost,
            'created_at'   => $this->createdAt,
            'carrier_name' => $this->carrierName
        ];
    }
}
