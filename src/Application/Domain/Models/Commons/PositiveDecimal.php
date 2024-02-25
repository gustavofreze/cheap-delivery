<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;

class PositiveDecimal
{
    private const SCALE = 2;

    public function __construct(public float $value)
    {
        if ($value <= 0) {
            throw new NonPositiveValue(class: static::class, value: $value);
        }
    }

    public function add(PositiveDecimal $addend): PositiveDecimal
    {
        $result = bcadd((string)$this->value, (string)$addend->value, self::SCALE);

        return new PositiveDecimal(value: (float)$result);
    }

    public function multiply(PositiveDecimal $multiplier): PositiveDecimal
    {
        $result = bcmul((string)$this->value, (string)$multiplier->value, self::SCALE);

        return new PositiveDecimal(value: (float)$result);
    }

    public function isLessThan(PositiveDecimal $other): bool
    {
        return $this->value < $other->value;
    }

    public function isGreaterThanOrEqual(PositiveDecimal $other): bool
    {
        return $this->value >= $other->value;
    }
}
