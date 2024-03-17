<?php

namespace CheapDelivery\Application\Domain\Models\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Weight;

final readonly class CompositeCost implements CostModality
{
    public function __construct(private CostModality $modalityOne, private CostModality $modalityTwo)
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

        $result = $costOne->add(addend: $costTwo);

        return new Cost(value: $result->value);
    }
}
