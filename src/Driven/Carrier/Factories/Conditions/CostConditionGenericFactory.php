<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Conditions;

use CheapDelivery\Domain\Models\Conditions\CostCondition;
use CheapDelivery\Domain\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Domain\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Domain\Models\Name;
use CheapDelivery\Domain\Models\Weight;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownCondition;
use MongoDB\Model\BSONDocument;

final class CostConditionGenericFactory implements CostConditionFactory
{
    public function __construct(private readonly BSONDocument $costCondition)
    {
    }

    public function build(): CostCondition
    {
        $name = new Name(value: $this->costCondition->name);
        $weight = new Weight(value: $this->costCondition->weight);

        return match ($name->getValue()) {
            self::WEIGHT_SMALLER_THAN          => new WeightSmallerThan(threshold: $weight),
            self::WEIGHT_GREATER_THAN_OR_EQUAL => new WeightGreaterThanOrEqual(threshold: $weight),
            default                            => throw new UnknownCondition(invalid: $name->getValue())
        };
    }
}
