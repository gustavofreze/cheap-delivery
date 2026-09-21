<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;

final readonly class Weight implements ValueObject
{
    use ValueObjectBehavior;

    private const string TYPE = 'Weight';

    private const float MAXIMUM = 1000.00;

    private function __construct(private Decimal $value)
    {
    }

    public static function from(float $value): Weight
    {
        $decimal = Decimal::of(value: $value);

        if ($decimal->isZero() || $decimal->isNegative()) {
            throw new NonPositiveValue(type: Weight::TYPE, value: $value);
        }

        if ($value > Weight::MAXIMUM) {
            throw new WeightOutOfRange(current: $value, maximum: Weight::MAXIMUM);
        }

        return new Weight(value: $decimal);
    }

    public function toFloat(): float
    {
        return $this->value->toFloat();
    }

    public function toDecimal(): Decimal
    {
        return $this->value;
    }

    public function isLessThan(Weight $other): bool
    {
        return $this->value->isLessThan(other: $other->value);
    }

    public function isGreaterThanOrEqual(Weight $other): bool
    {
        return $this->value->isGreaterThanOrEqual(other: $other->value);
    }
}
