<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

final class Shipment
{
    public function __construct(private Cost $cost, private Name $carrier)
    {
    }

    public function getCost(): Cost
    {
        return $this->cost;
    }

    public function getCarrier(): Name
    {
        return $this->carrier;
    }

    public function toArray(): array
    {
        return [
            'carrier' => $this->carrier->getValue(),
            'cost' => $this->cost->getValue()
        ];
    }
}
