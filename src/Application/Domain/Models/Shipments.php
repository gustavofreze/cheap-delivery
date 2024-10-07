<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use TinyBlocks\Collection\Collection;

final class Shipments extends Collection
{
    public static function from(Weight $weight, Distance $distance, Carriers $carriers): Shipments
    {
        if ($carriers->isEmpty()) {
            throw new NoCarriersAvailable();
        }

        $shipments = $carriers
            ->map(transformations: fn(Carrier $carrier) => $carrier->shipment(weight: $weight, distance: $distance));

        return Shipments::createFrom(elements: $shipments);
    }
}
