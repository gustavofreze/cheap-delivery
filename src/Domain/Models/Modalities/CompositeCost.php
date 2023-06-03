<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;

final class CompositeCost implements CostModality
{
    public function __construct(private readonly CostModality $modalityOne, private readonly CostModality $modalityTwo)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $costOne = $this->modalityOne->calculate(weight: $weight, distance: $distance);
        $costTwo = $this->modalityTwo->calculate(weight: $weight, distance: $distance);

        if (is_null($costOne)) {
            return $costTwo;
        }

        if (is_null($costTwo)) {
            return $costOne;
        }

        return $costOne->plus(cost: $costTwo);
    }
}
