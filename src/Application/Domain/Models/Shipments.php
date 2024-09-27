<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use TinyBlocks\Collection\Collectible;
use TinyBlocks\Collection\Collection;

final class Shipments extends Collection
{
    public static function from(
        Weight $weight,
        Distance $distance,
        Carriers|Collectible $carriers
    ): Shipments|Collectible {
        if ($carriers->isEmpty()) {
            throw new NoCarriersAvailable();
        }

        $shipments = $carriers->map(transformations: fn(Carrier $carrier) => $carrier->shipment(
            weight: $weight,
            distance: $distance
        ));

        return Shipments::createFrom(elements: $shipments);
    }
}
