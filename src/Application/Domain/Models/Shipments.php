<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Models\Commons\Collection;

final class Shipments extends Collection
{
    public static function from(Weight $weight, Distance $distance, Carriers $carriers): Shipments
    {
        if ($carriers->isEmpty()) {
            throw new NoCarriersAvailable();
        }

        $shipments = $carriers->map(callback: fn(Carrier $carrier) => $carrier->shipment(
            weight: $weight,
            distance: $distance
        ));

        return new Shipments(elements: $shipments);
    }
}
