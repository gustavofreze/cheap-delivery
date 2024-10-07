<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\DistanceOutOfRange;
use CheapDelivery\Application\Domain\Models\Commons\PositiveDecimal;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class Distance extends PositiveDecimal implements ValueObject
{
    use ValueObjectBehavior;

    private const MAXIMUM_DISTANCE = 20000.00;

    public function __construct(public float $value)
    {
        if ($value > self::MAXIMUM_DISTANCE) {
            throw new DistanceOutOfRange(current: $value, maximum: self::MAXIMUM_DISTANCE);
        }

        parent::__construct(value: $value);
    }
}
