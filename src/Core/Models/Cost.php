<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

use LogicException;

final class Cost
{
    public function __construct(private float $value)
    {
        if ($this->value <= 0) {
            throw new LogicException('Cost cannot be zero or negative');
        }
    }

    public function plus(Cost $cost): Cost
    {
        return new Cost($this->value + $cost->value);
    }

    public function isGreaterThan(Cost $cost): bool
    {
        return $this->value > $cost->value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
