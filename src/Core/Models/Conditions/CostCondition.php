<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Conditions;

use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

interface CostCondition
{
    public function apply(Weight $weight, Distance $distance): bool;
}
