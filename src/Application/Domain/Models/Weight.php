<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class Weight extends PositiveDecimal implements ValueObject
{
    use ValueObjectBehavior;

    private const float MAXIMUM_WEIGHT = 1000.00;

    public function __construct(public float $value)
    {
        if ($value > self::MAXIMUM_WEIGHT) {
            throw new WeightOutOfRange(current: $value, maximum: self::MAXIMUM_WEIGHT);
        }

        parent::__construct(value: $value);
    }
}
