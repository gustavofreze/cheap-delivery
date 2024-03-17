<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;

final class Distance extends PositiveDecimal
{
    private const MAXIMUM_DISTANCE = 20000.00;

    public function __construct(float $value)
    {
        parent::__construct(value: $value);

        if ($this->value > self::MAXIMUM_DISTANCE) {
            throw new DistanceOutOfRange(current: $this->value, maximum: self::MAXIMUM_DISTANCE);
        }
    }
}
