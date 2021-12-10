<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Modalities;

use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

final class CompositeCost implements CostModality
{
    public function __construct(private CostModality $modalityOne, private CostModality $modalityTwo)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $costOne = $this->modalityOne->calculate($weight, $distance);
        $costTwo = $this->modalityTwo->calculate($weight, $distance);

        if (is_null($costOne)) {
            return $costTwo;
        }

        if (is_null($costTwo)) {
            return $costOne;
        }

        return $costOne->plus($costTwo);
    }
}
