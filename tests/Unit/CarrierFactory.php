<?php

declare(strict_types=1);

namespace Test\Unit;

use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Carrier\Carriers;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Application\Domain\Models\Carrier\Conditions\WeightSmallerThan;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\FixedCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\LinearCost;
use CheapDelivery\Application\Domain\Models\Carrier\Modalities\PartialCost;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class CarrierFactory
{
    public const float LOGGI_WEIGHT_THRESHOLD = 5.00;

    public static function priced(float $rate, float $fixed): CostModality
    {
        return CompositeCost::from(
            first: FixedCost::from(cost: Cost::from(value: $fixed)),
            second: LinearCost::from(rate: Cost::from(value: $rate))
        );
    }

    public static function registered(): Carriers
    {
        $threshold = Weight::from(value: CarrierFactory::LOGGI_WEIGHT_THRESHOLD);

        return Carriers::createFrom(elements: [
            Carrier::from(
                name: Name::from(value: 'DHL'),
                modality: CarrierFactory::priced(rate: 0.05, fixed: 10.00)
            ),
            Carrier::from(
                name: Name::from(value: 'FedEx'),
                modality: CarrierFactory::priced(rate: 0.12, fixed: 4.30)
            ),
            Carrier::from(
                name: Name::from(value: 'Loggi'),
                modality: PartialCost::from(
                    modality: CarrierFactory::priced(rate: 1.10, fixed: 2.10),
                    condition: WeightSmallerThan::from(threshold: $threshold)
                )
            ),
            Carrier::from(
                name: Name::from(value: 'Loggi'),
                modality: PartialCost::from(
                    modality: CarrierFactory::priced(rate: 0.01, fixed: 10.00),
                    condition: WeightGreaterThanOrEqual::from(threshold: $threshold)
                )
            )
        ]);
    }
}
