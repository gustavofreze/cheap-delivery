<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Conditions;

use CheapDelivery\Core\Models\Conditions\CostCondition;
use CheapDelivery\Core\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Core\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Weight;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownCondition;
use MongoDB\Model\BSONDocument;

final class CostConditionGenericFactory implements CostConditionFactory
{
    public function __construct(private BSONDocument $costCondition)
    {
    }

    public function build(): CostCondition
    {
        $name = new Name($this->costCondition->name);
        $weight = new Weight($this->costCondition->weight);

        return match ($name->getValue()) {
            self::WEIGHT_SMALLER_THAN => new WeightSmallerThan(threshold: $weight),
            self::WEIGHT_GREATER_THAN_OR_EQUAL => new WeightGreaterThanOrEqual(threshold: $weight),
            default => throw new UnknownCondition(invalid: $name->getValue())
        };
    }
}
