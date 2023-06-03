<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Conditions;

use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;

interface CostCondition
{
    public function apply(Weight $weight, Distance $distance): bool;
}
