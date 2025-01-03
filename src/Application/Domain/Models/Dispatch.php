<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Events\DispatchedWithLowestCost;
use CheapDelivery\Application\Domain\Events\Events;
use CheapDelivery\Application\Domain\Exceptions\NoEligibleCarriers;
use CheapDelivery\Application\Domain\Models\Commons\Utc;
use TinyBlocks\Collection\Order;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class Dispatch implements ValueObject
{
    use ValueObjectBehavior;

    private Events $events;

    public function __construct(public DispatchId $id, public ?Shipment $shipment = null)
    {
        $this->events = Events::createFromEmpty();
    }

    public static function create(): Dispatch
    {
        return new Dispatch(id: DispatchId::create());
    }

    public function dispatchWithLowestCost(Weight $weight, Distance $distance, Carriers $carriers): Dispatch
    {
        $shipments = Shipments::from(weight: $weight, distance: $distance, carriers: $carriers);

        $shipment = $shipments
            ->filter()
            ->map(transformations: fn(Shipment $shipment) => $shipment)
            ->sort(
                order: Order::ASCENDING_VALUE,
                predicate: fn(Shipment $first, Shipment $second) => $first->cost->value <=> $second->cost->value
            )
            ->first();

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
        return Events::createFrom(elements: $this->events);
    }
}
