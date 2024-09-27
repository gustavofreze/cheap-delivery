<?php

namespace CheapDelivery\Application\Domain\Factories;

use CheapDelivery\Application\Domain\Models\Carrier;
use CheapDelivery\Application\Domain\Models\Carriers as CarriersModel;
use CheapDelivery\Application\Domain\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Weight;
use TinyBlocks\Collection\Collectible;

final class Carriers
{
    public static function available(): Collectible|CarriersModel
    {
        return CarriersModel::createFrom(elements: [
            Model::carrierFromDHL(),
            Model::carrierFromFedEx(),
            Model::carrierFromLoggi()
        ]);
    }

    public static function unavailable(): Collectible|CarriersModel
    {
        return CarriersModel::createFromEmpty();
    }

    public static function noEligible(): Collectible|CarriersModel
    {
        return CarriersModel::createFrom(elements: [
            new Carrier(
                name: new Name(value: 'FedEx'),
                costModality: new CompositeCost(
                    modalityOne: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(fixedCost: new Cost(value: 5.00)),
                            modalityTwo: new LinearCost(variableCost: new Cost(value: 4.50))
                        ),
                        condition: new WeightSmallerThan(threshold: new Weight(value: 50.0))
                    ),
                    modalityTwo: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(fixedCost: new Cost(value: 10.0)),
                            modalityTwo: new LinearCost(variableCost: new Cost(value: 9.00))
                        ),
                        condition: new WeightGreaterThanOrEqual(threshold: new Weight(value: 100.0))
                    )
                )
            )
        ]);
    }
}
