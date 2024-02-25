<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;

final class ShipmentId implements Identity
{
    private function __construct(public Uuid $value)
    {
    }

    public static function create(?Uuid $value = null): ShipmentId
    {
        if (is_null($value)) {
            $value = Uuid::generateV4();
        }

        return new ShipmentId(value: $value);
    }

    public function getValue(): string
    {
        return $this->value->toString();
    }

    public function equals(Identity $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
