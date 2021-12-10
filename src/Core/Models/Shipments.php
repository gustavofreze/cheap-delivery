<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

final class Shipments
{
    public function __construct(private array $shipments)
    {
    }

    public static function from(array $carriers, Weight $weight, Distance $distance): Shipments
    {
        $results = array_map(fn(Carrier $carrier) => $carrier->shipment($weight, $distance), $carriers);
        $shipments = array_filter($results, fn(?Shipment $carrier) => !is_null($carrier));

        return new Shipments($shipments);
    }

    public function lowestCost(): ?Shipment
    {
        /** @var ?Shipment $lowestCost */
        $lowestCost = null;

        /** @var Shipment $shipment */
        foreach ($this->shipments as $shipment) {
            if (is_null($lowestCost) || $lowestCost->getCost()->isGreaterThan($shipment->getCost())) {
                $lowestCost = $shipment;
            }
        }

        return $lowestCost;
    }
}
