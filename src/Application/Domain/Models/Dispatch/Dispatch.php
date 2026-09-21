<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Events\Commons\EventualAggregateRoot;
use CheapDelivery\Application\Domain\Events\Commons\EventualAggregateRootBehavior;
use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Models\Carrier\Carriers;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final class Dispatch implements EventualAggregateRoot
{
    use EventualAggregateRootBehavior;

    private function __construct(public DispatchId $id, public Shipment $shipment)
    {
    }

    public static function dispatchWithLowestCost(
        DispatchId $id,
        Weight $weight,
        Carriers $carriers,
        Distance $distance
    ): Dispatch {
        $shipment = Shipments::quotedBy(weight: $weight, carriers: $carriers, distance: $distance)->cheapest();

        if (is_null($shipment)) {
            throw new NoEligibleCarriers();
        }

        $dispatch = new Dispatch(id: $id, shipment: $shipment);

        $dispatch->pushEvent(event: new DispatchedWithLowestCost(shipment: $shipment));

        return $dispatch;
    }
}
