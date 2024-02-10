<?php

namespace CheapDelivery\Application\Handlers;

use CheapDelivery\Application\Commands\CalculateShipment;
use CheapDelivery\Application\Commands\Command;
use CheapDelivery\Application\Domain\Models\Shipments;
use CheapDelivery\Application\Ports\Inbound\CommandHandler;
use CheapDelivery\Application\Ports\Outbound\CarriersRepository;
use CheapDelivery\Application\Ports\Outbound\ShipmentsRepository;

final readonly class CalculateShipmentHandler implements CommandHandler
{
    public function __construct(private CarriersRepository $carriers, private ShipmentsRepository $shipment)
    {
    }

    public function handle(Command|CalculateShipment $command): void
    {
        $carriers = $this->carriers->findAll();

        $shipments = Shipments::from(
            weight: $command->product->weight,
            distance: $command->person->distance,
            carriers: $carriers
        );

        $shipment = $shipments->lowestCost();
        $this->shipment->save(shipment: $shipment);
    }
}
