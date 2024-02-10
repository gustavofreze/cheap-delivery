<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Identity;
use CheapDelivery\Application\Domain\Models\Commons\Uuid;

final class ShipmentId implements Identity
{
    public function __construct(public ?Uuid $value = null)
    {
        if (is_null($value)) {
            $value = Uuid::generateV4();
        }

        $this->value = $value;
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
