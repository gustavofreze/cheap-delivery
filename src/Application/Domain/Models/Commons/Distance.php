<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;

final readonly class Distance implements ValueObject
{
    use ValueObjectBehavior;

    private const string TYPE = 'Distance';

    private const float MAXIMUM = 20000.00;

    private function __construct(private Decimal $value)
    {
    }

    public static function from(float $value): Distance
    {
        $decimal = Decimal::of(value: $value);

        if ($decimal->isZero() || $decimal->isNegative()) {
            throw new NonPositiveValue(type: Distance::TYPE, value: $value);
        }

        if ($value > Distance::MAXIMUM) {
            throw new DistanceOutOfRange(current: $value, maximum: Distance::MAXIMUM);
        }

        return new Distance(value: $decimal);
    }

    public function toFloat(): float
    {
        return $this->value->toFloat();
    }

    public function toDecimal(): Decimal
    {
        return $this->value;
    }
}
