<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class FixedCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = (string)($this->costModality['modality'] ?? '');

        if ($modality !== self::FIXED) {
            throw new WrongModality(invalid: $modality, expected: self::FIXED);
        }
    }

    public function build(): CostModality
    {
        return FixedCost::from(cost: Cost::from(value: (float)$this->costModality['cost']));
    }
}
