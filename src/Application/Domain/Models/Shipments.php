<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Events\CalculatedShipmentCost;
use CheapDelivery\Application\Domain\Events\Events;
use CheapDelivery\Application\Domain\Exceptions\NoCarriersAvailable;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Models\Commons\Collectible;
use CheapDelivery\Application\Domain\Models\Commons\Collection;
use CheapDelivery\Application\Domain\Models\Commons\Utc;

final class Shipments implements Collectible
{
    use Collection;

    private Events $events;

    private function __construct(private readonly Collectible $shipments)
    {
        $this->events = new Events();
    }

    public static function from(Weight $weight, Distance $distance, Carriers $carriers): Shipments
    {
        if ($carriers->isEmpty()) {
            throw new NoCarriersAvailable();
        }

        $shipments = $carriers->map(callback: fn(Carrier $carrier) => $carrier->shipment(
            weight: $weight,
            distance: $distance
        ));

        return new Shipments(shipments: $shipments);
    }

    public function lowestCost(): Shipment
    {
        $shipment = $this->shipments
            ->filter()
            ->map(callback: fn(Shipment $shipment) => $shipment)
            ->minBy(callback: fn(Shipment $shipment) => $shipment->cost->value);

        if (is_null($shipment)) {
            throw new NoEligibleCarriers();
        }

        $event = new CalculatedShipmentCost(id: $shipment->id, shipment: $shipment, instant: Utc::now());
        $this->events->add(item: $event);

        return $shipment;
    }

    public function occurredEvents(): Events
    {
        return new Events(items: $this->events->all());
    }
}
