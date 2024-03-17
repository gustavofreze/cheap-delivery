<?php

namespace  CheapDelivery\Application\Domain\Factories;

use CheapDelivery\Application\Domain\Models\Carrier;
use CheapDelivery\Application\Domain\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Weight;

final class Model
{
    public static function carrierFromDHL(): Carrier
    {
        return new Carrier(
            name: new Name(value: 'DHL'),
            costModality: new CompositeCost(
                modalityOne: new FixedCost(fixedCost: new Cost(value: 10.0)),
                modalityTwo: new LinearCost(variableCost: new Cost(value: 0.05))
            )
        );
    }

    public static function carrierFromFedEx(): Carrier
    {
        return new Carrier(
            name: new Name(value: 'FedEx'),
            costModality: new CompositeCost(
                modalityOne: new FixedCost(fixedCost: new Cost(value: 4.30)),
                modalityTwo: new LinearCost(variableCost: new Cost(value: 0.12))
            )
        );
    }

    public static function carrierFromLoggi(): Carrier
    {
        return new Carrier(
            name: new Name(value: 'Loggi'),
            costModality: new CompositeCost(
                modalityOne: new PartialCost(
                    modality: new CompositeCost(
                        modalityOne: new FixedCost(fixedCost: new Cost(value: 2.10)),
                        modalityTwo: new LinearCost(variableCost: new Cost(value: 0.12))
                    ),
                    condition: new WeightSmallerThan(threshold: new Weight(value: 5.0))
                ),
                modalityTwo: new PartialCost(
                    modality: new CompositeCost(
                        modalityOne: new FixedCost(fixedCost: new Cost(value: 10.0)),
                        modalityTwo: new LinearCost(variableCost: new Cost(value: 0.01))
                    ),
                    condition: new WeightGreaterThanOrEqual(threshold: new Weight(value: 5.0))
                )
            )
        );
    }
}
