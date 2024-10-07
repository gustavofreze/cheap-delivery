<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Commons;

use CheapDelivery\Application\Domain\Exceptions\NonPositiveValue;
use TinyBlocks\Math\Internal\Exceptions\NonPositiveValue as NonPositiveNumberException;
use TinyBlocks\Math\PositiveBigDecimal;

class PositiveDecimal extends PositiveBigDecimal
{
    private const SCALE = 2;

    public function __construct(public float $value)
    {
        try {
            parent::__construct(value: $value, scale: self::SCALE);
        } catch (NonPositiveNumberException) {
            throw new NonPositiveValue(class: static::class, value: $value);
        }
    }
}
