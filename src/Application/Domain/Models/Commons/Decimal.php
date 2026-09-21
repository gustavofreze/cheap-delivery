<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use TinyBlocks\Math\BigDecimal;
use TinyBlocks\Math\RoundingMode;

final readonly class Decimal implements ValueObject
{
    use ValueObjectBehavior;

    public const int SCALE = 2;

    private function __construct(private BigDecimal $number)
    {
    }

    public static function of(float $value): Decimal
    {
        $number = BigDecimal::fromFloat(value: $value);

        return new Decimal(number: $number->toScale(scale: Decimal::SCALE, rounding: RoundingMode::HalfUp));
    }

    public function plus(Decimal $addend): Decimal
    {
        return new Decimal(number: $this->number->plus(addend: $addend->number));
    }

    public function isZero(): bool
    {
        return $this->number->isZero();
    }

    public function toFloat(): float
    {
        return $this->number->toFloat();
    }

    public function isNegative(): bool
    {
        return $this->number->isNegative();
    }

    public function isLessThan(Decimal $other): bool
    {
        return $this->number->compareTo(other: $other->number) < 0;
    }

    public function multipliedBy(Decimal $multiplier): Decimal
    {
        return new Decimal(number: $this->number->multipliedBy(multiplier: $multiplier->number));
    }

    public function isGreaterThanOrEqual(Decimal $other): bool
    {
        return $this->number->compareTo(other: $other->number) >= 0;
    }
}
