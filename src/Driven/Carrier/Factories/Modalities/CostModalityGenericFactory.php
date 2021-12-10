<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownModality;
use MongoDB\Model\BSONDocument;

final class CostModalityGenericFactory implements CostModalityFactory
{
    public function __construct(private BSONDocument $costModality)
    {
    }

    public function build(): CostModality
    {
        $modality = $this->costModality->modality;

        return match ($modality) {
            self::FIXED => (new FixedCostFactory($this->costModality))->build(),
            self::LINEAR => (new LinearCostFactory($this->costModality))->build(),
            self::PARTIAL => (new PartialCostFactory($this->costModality))->build(),
            self::COMPOSITE => (new CompositeCostFactory($this->costModality))->build(),
            default => throw new UnknownModality(invalid: $modality)
        };
    }
}
