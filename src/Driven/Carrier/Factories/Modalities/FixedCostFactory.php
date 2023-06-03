<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Modalities\CostModality;
use CheapDelivery\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class FixedCostFactory implements CostModalityFactory
{
    public function __construct(private readonly BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::FIXED) {
            throw new WrongModality(invalid: $modality, expected: self::FIXED);
        }
    }

    public function build(): CostModality
    {
        return new FixedCost(fixedCost: new Cost(value: floatval($this->costModality->cost)));
    }
}
