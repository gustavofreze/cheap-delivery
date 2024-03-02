<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Events\Events;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Models\Commons\Utc;

final class Dispatch
{
    private Events $events;

    public function __construct(public DispatchId $id, public ?Shipment $shipment = null)
    {
        $this->events = new Events();
    }

    public static function create(): Dispatch
    {
        return new Dispatch(id: DispatchId::create());
    }

    public function dispatchWithLowestCost(Weight $weight, Distance $distance, Carriers $carriers): Dispatch
    {
        $shipments = Shipments::from(weight: $weight, distance: $distance, carriers: $carriers);

        /** @var Shipment|null $shipment */
        $shipment = $shipments
            ->filter()
            ->map(callback: fn(Shipment $shipment) => $shipment)
            ->minBy(callback: fn(Shipment $shipment) => $shipment->cost->value);

        if (is_null($shipment)) {
            throw new NoEligibleCarriers();
        }

        $this->shipment = $shipment;

        $event = new DispatchedWithLowestCost(id: $this->id, dispatch: $this, instant: Utc::now());
        $this->events->add(element: $event);

        return $this;
    }

    public function occurredEvents(): Events
    {
        return new Events(elements: $this->events->all());
    }
}
