<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\WeightOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;

final class Weight extends PositiveDecimal
{
    private const MAXIMUM_WEIGHT = 1000.00;

    public function __construct(float $value)
    {
        parent::__construct(value: $value);

        if ($this->value > self::MAXIMUM_WEIGHT) {
            throw new WeightOutOfRange(current: $this->value, maximum: self::MAXIMUM_WEIGHT);
        }
    }
}
