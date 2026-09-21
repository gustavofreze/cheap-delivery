<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;

final readonly class Cost implements ValueObject
{
    use ValueObjectBehavior;

    private const string TYPE = 'Cost';

    private function __construct(private Decimal $value)
    {
    }

    public static function from(float $value): Cost
    {
        $decimal = Decimal::of(value: $value);

        if ($decimal->isZero() || $decimal->isNegative()) {
            throw new NonPositiveValue(type: Cost::TYPE, value: $value);
        }

        return new Cost(value: $decimal);
    }

    public function plus(Cost $addend): Cost
    {
        return Cost::from(value: $this->value->plus(addend: $addend->value)->toFloat());
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
