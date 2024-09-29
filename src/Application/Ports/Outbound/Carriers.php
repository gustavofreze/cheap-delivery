<?php

namespace CheapDelivery\Application\Ports\Outbound;

use CheapDelivery\Application\Domain\Models\Carriers as CarriersCollection;

interface Carriers
{
    /**
     * Retrieves all carriers from the repository.
     *
     * @return CarriersCollection The collection of carriers.
     */
    public function findAll(): CarriersCollection;
}
