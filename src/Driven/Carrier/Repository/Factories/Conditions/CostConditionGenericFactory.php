<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Conditions;

use CheapDelivery\Application\Domain\Models\Carrier\Conditions\CostCondition;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownCondition;

final readonly class CostConditionGenericFactory implements CostConditionFactory
{
    public function __construct(private array $costCondition)
    {
    }

    public function build(): CostCondition
    {
        $name = (string)($this->costCondition['name'] ?? '');

        if (!in_array($name, [self::WEIGHT_SMALLER_THAN, self::WEIGHT_GREATER_THAN_OR_EQUAL], true)) {
            throw new UnknownCondition(invalid: $name);
        }

        $threshold = Weight::from(value: (float)($this->costCondition['weight'] ?? 0.0));

        return $name === self::WEIGHT_SMALLER_THAN
            ? WeightSmallerThan::from(threshold: $threshold)
            : WeightGreaterThanOrEqual::from(threshold: $threshold);
    }
}
