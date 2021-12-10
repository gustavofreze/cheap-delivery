<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

use LogicException;

final class Distance
{
    public function __construct(private float $value)
    {
        if ($this->value <= 0) {
            throw new LogicException('Distance cannot be zero or negative');
        }
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
