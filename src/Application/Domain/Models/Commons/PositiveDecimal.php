<?php

namespace CheapDelivery\Application\Domain\Models\Commons;

use AllowDynamicProperties;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;

#[AllowDynamicProperties]
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
        $result = bcadd($this->value, $addend->value, self::SCALE);

        return new PositiveDecimal(value: $result);
    }

    public function multiply(PositiveDecimal $multiplier): PositiveDecimal
    {
        $result = bcmul($this->value, $multiplier->value, self::SCALE);

        return new PositiveDecimal(value: $result);
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
