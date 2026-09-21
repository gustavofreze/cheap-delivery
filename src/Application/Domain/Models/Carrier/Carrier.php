<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier;

use CheapDelivery\Application\Domain\Models\Carrier\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\ValueObject;
use CheapDelivery\Application\Domain\Models\Commons\ValueObjectBehavior;
use CheapDelivery\Application\Domain\Models\Commons\Weight;
use CheapDelivery\Application\Domain\Models\Dispatch\Shipment;

final readonly class Carrier implements ValueObject
{
    use ValueObjectBehavior;

    private function __construct(public Name $name, private CostModality $modality)
    {
    }

    public static function from(Name $name, CostModality $modality): Carrier
    {
        return new Carrier(name: $name, modality: $modality);
    }

    public function quote(Weight $weight, Distance $distance): ?Shipment
    {
        $cost = $this->modality->calculate(weight: $weight, distance: $distance);

        return is_null($cost) ? null : Shipment::from(cost: $cost, carrierName: $this->name);
    }
}
