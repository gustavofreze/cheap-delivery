<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Conditions;

use CheapDelivery\Application\Domain\Models\Conditions\CostCondition;
use CheapDelivery\Application\Domain\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Weight;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownCondition;

final readonly class CostConditionGenericFactory implements CostConditionFactory
{
    public function __construct(private array $costCondition)
    {
    }

    public function build(): CostCondition
    {
        $name = new Name(value: $this->costCondition['name']);
        $weight = new Weight(value: $this->costCondition['weight']);

        return match ($name->value) {
            self::WEIGHT_SMALLER_THAN          => new WeightSmallerThan(threshold: $weight),
            self::WEIGHT_GREATER_THAN_OR_EQUAL => new WeightGreaterThanOrEqual(threshold: $weight),
            default                            => throw new UnknownCondition(invalid: $name->value)
        };
    }
}
