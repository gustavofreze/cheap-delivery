<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Modalities;

use CheapDelivery\Core\Models\Conditions\CostCondition;
use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

final class PartialCost implements CostModality
{
    public function __construct(private CostModality $modality, private CostCondition $condition)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        if (!$this->condition->apply($weight, $distance)) {
            return null;
        }

        return $this->modality->calculate($weight, $distance);
    }
}
