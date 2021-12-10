<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

use LogicException;

final class Weight
{
    public function __construct(private float $value)
    {
        if ($this->value <= 0) {
            throw new LogicException('Weight cannot be zero or negative');
        }
    }

    public function smallerThan(Weight $weight): bool
    {
        return $this->value < $weight->value;
    }

    public function greaterThanOrEqual(Weight $weight): bool
    {
        return $this->value >= $weight->value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
