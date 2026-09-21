<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class CompositeCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = (string)($this->costModality['modality'] ?? '');

        if ($modality !== self::COMPOSITE) {
            throw new WrongModality(invalid: $modality, expected: self::COMPOSITE);
        }
    }

    public function build(): CostModality
    {
        return CompositeCost::from(
            first: new CostModalityGenericFactory(costModality: $this->costModality['modalityOne'])->build(),
            second: new CostModalityGenericFactory(costModality: $this->costModality['modalityTwo'])->build()
        );
    }
}
