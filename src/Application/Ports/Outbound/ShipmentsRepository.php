<?php

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Shipment;

interface ShipmentsRepository
{
    /**
     * Saves a Shipment object to the repository.
     *
     * @param Shipment $shipment The Shipment object to be saved.
     */
    public function save(Shipment $shipment): void;
}
