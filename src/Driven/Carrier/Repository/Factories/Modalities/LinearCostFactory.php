<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class LinearCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = (string)($this->costModality['modality'] ?? '');

        if ($modality !== self::LINEAR) {
            throw new WrongModality(invalid: $modality, expected: self::LINEAR);
        }
    }

    public function build(): CostModality
    {
        return LinearCost::from(rate: Cost::from(value: (float)$this->costModality['cost']));
    }
}
