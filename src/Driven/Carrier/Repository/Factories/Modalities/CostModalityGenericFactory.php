<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownModality;

final readonly class CostModalityGenericFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
    }

    public function build(): CostModality
    {
        $modality = (string)($this->costModality['modality'] ?? '');

        return match ($modality) {
            self::FIXED     => new FixedCostFactory(costModality: $this->costModality)->build(),
            self::LINEAR    => new LinearCostFactory(costModality: $this->costModality)->build(),
            self::PARTIAL   => new PartialCostFactory(costModality: $this->costModality)->build(),
            self::COMPOSITE => new CompositeCostFactory(costModality: $this->costModality)->build(),
            default         => throw new UnknownModality(invalid: $modality)
        };
    }
}
