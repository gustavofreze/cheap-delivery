<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Carrier\Carriers;
use CheapDelivery\Application\Domain\Models\Commons\Collection;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final class Shipments extends Collection
{
    public static function quotedBy(Weight $weight, Carriers $carriers, Distance $distance): Shipments
    {
        if ($carriers->isEmpty()) {
            throw new NoCarriersAvailable();
        }

        $quotes = $carriers
            ->map(transformations: fn(Carrier $carrier): ?Shipment => $carrier->quote(
                weight: $weight,
                distance: $distance
            ))
            ->filter(predicates: fn(?Shipment $shipment): bool => !is_null($shipment));

        return Shipments::createFrom(elements: $quotes);
    }

    public function cheapest(): ?Shipment
    {
        return $this
            ->sortedByValue(
                comparator: fn(Shipment $first, Shipment $second): int
                    => ($first->cost->toFloat() <=> $second->cost->toFloat())
            )
            ->first();
    }
}
