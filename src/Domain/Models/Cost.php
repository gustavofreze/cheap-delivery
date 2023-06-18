<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;

final class Cost
{
    public function __construct(private readonly float $value)
    {
        if ($this->value <= 0) {
            throw new LogicException(message: 'Cost cannot be zero or negative.');
        }
    }

    public function plus(Cost $cost): Cost
    {
        return new Cost(value: $this->value + $cost->value);
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
